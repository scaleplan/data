<?php

namespace Scaleplan\Data\Cache;

use Scaleplan\Data\CacheStructure;
use Scaleplan\Data\Exceptions\MemcachedCacheException;
use Scaleplan\Data\Exceptions\MemcachedOperationException;
use Scaleplan\Data\TagStructure;

/**
 * Class MemcachedCache
 *
 * @package Scaleplan\Data\Cache
 */
class MemcachedCache implements CacheInterface
{
    public const PERSISTENT_ID = 589475;

    /**
     * @var \Memcached
     */
    protected $memcached;

    /**
     * @var string
     */
    protected $databaseKeyPostfix;

    /**
     * MemcachedCache constructor.
     *
     * @param bool $isPconnect
     */
    public function __construct(bool $isPconnect = null)
    {
        $this->memcached = ($isPconnect ?? (bool)getenv(self::CACHE_PCONNECT_ENV))
            ? new \Memcached(static::PERSISTENT_ID)
            : new \Memcached();
    }

    /**
     * @return \Memcached
     *
     * @throws MemcachedCacheException
     */
    public function getCacheConnect() : \Memcached
    {
        if ($this->memcached->getServerList()) {
            $this->memcached;
        }

        $hostOrSocket = getenv(self::CACHE_HOST_OR_SOCKET_ENV);
        $port = getenv(self::CACHE_PORT_ENV);
        if (!$hostOrSocket || !$hostOrSocket) {
            throw new MemcachedCacheException('Недостаточно даных для подключения к Memcached.');
        }

        if ($this->memcached->addServer($hostOrSocket, $port)) {
            return $this->memcached;
        }

        throw new MemcachedCacheException('Не удалось подключиться к Memcached');
    }

    /**
     * @param string $dbName
     */
    public function selectDatabase(string $dbName) : void
    {
        $this->databaseKeyPostfix = $dbName;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getKey(string $key) : string
    {
        return $key . $this->databaseKeyPostfix;
    }

    /**
     * @param string $key
     * @param CacheStructure $value
     * @param int $ttl
     *
     * @throws MemcachedCacheException
     * @throws MemcachedOperationException
     */
    public function set(string $key, CacheStructure $value, int $ttl = null) : void
    {
        $ttl = $ttl ?? (getenv(self::CACHE_TIMEOUT_ENV) ?: 0);
        if (!$this->getCacheConnect()->set($this->getKey($key), (string)$value, $ttl)) {
            throw new MemcachedOperationException('Операция записи по ключу не удалась.');
        }
    }

    /**
     * @param TagStructure[] $tags
     *
     * @throws MemcachedCacheException
     * @throws MemcachedOperationException
     */
    public function initTags(array $tags) : void
    {
        /** @var TagStructure $value */
        foreach ($tags as &$value) {
            if (!$this->getCacheConnect()->set($this->getKey($value->getName()), (string)$value)) {
                throw new MemcachedOperationException('Операция инициализации тегов не удалась.');
            }
        }
        unset($value);
    }

    /**
     * @param array $tags
     *
     * @return TagStructure[]
     *
     * @throws MemcachedCacheException
     */
    public function getTagsData(array $tags) : array
    {
        $result = [];
        foreach ($tags as $tag) {
            $tagData = json_decode($this->getCacheConnect()->get($this->getKey($tag)), true);
            if (!$tag) {
                continue;
            }

            $result[$tag] = new TagStructure($tagData);
            $result[$tag]->setName($tag);
        }

        return $result;
    }

    /**
     * @param string $key
     *
     * @return CacheStructure
     *
     * @throws MemcachedCacheException
     */
    public function get(string $key) : CacheStructure
    {
        return new CacheStructure((array)json_decode($this->getCacheConnect()->get($this->getKey($key)), true));
    }

    /**
     * @param string $key
     *
     * @throws MemcachedCacheException
     */
    public function delete(string $key) : void
    {
        $this->getCacheConnect()->delete($this->getKey($key));
    }
}
