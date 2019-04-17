<?php

namespace Scaleplan\Data\Cache;

use Scaleplan\Data\CacheStructure;
use Scaleplan\Data\Exceptions\RedisCacheException;
use Scaleplan\Data\Exceptions\RedisOperationException;
use Scaleplan\Data\TagStructure;

/**
 * Class RedisCache
 *
 * @package Scaleplan\Data\Cache
 */
class RedisCache implements CacheInterface
{
    public const RESERVED = null;
    public const RETRY_INTERVAL = 0;

    /**
     * @var \Redis
     */
    protected $redis;

    /**
     * @var string
     */
    protected $databaseKeyPrefix;

    /**
     * @var bool
     */
    protected $isPconnect;

    /**
     * RedisCache constructor.
     *
     * @param bool $isPconnect
     */
    public function __construct(bool $isPconnect = null)
    {
        $this->redis = new \Redis();
        $this->isPconnect = $isPconnect ?? (bool)getenv(static::CACHE_PCONNECT_ENV);
    }

    /**
     * @return \Redis
     *
     * @throws RedisCacheException
     */
    public function getCacheConnect() : \Redis
    {
        if ($this->redis->isConnected()) {
            return $this->redis;
        }

        $hostOrSocket = getenv(self::CACHE_HOST_OR_SOCKET_ENV);
        $port = getenv(self::CACHE_PORT_ENV);
        $timeout = getenv(self::CACHE_TIMEOUT_ENV) ?: 0;
        if (!$hostOrSocket || !$hostOrSocket) {
            throw new RedisCacheException('Недостаточно даных для подключения к Redis.');
        }

        if ($this->isPconnect) {
            $connectMethod = 'pconnect';
        } else {
            $connectMethod = 'connect';
        }

        if ($this->redis->$connectMethod($hostOrSocket, $port, $timeout, static::RESERVED, static::RETRY_INTERVAL)) {
            return $this->redis;
        }

        throw new RedisCacheException ("Не удалось подключиться к хосту/сокету $hostOrSocket");
    }

    /**
     * @param string $dbName
     */
    public function selectDatabase(string $dbName) : void
    {
        $this->databaseKeyPrefix = $dbName;
    }

    /**
     * @param string $key
     *
     * @return CacheStructure
     *
     * @throws RedisCacheException
     */
    public function get(string $key) : CacheStructure
    {
        return new CacheStructure((array)json_decode($this->getCacheConnect()->get($key), true));
    }

    /**
     * @param TagStructure[] $tags
     *
     * @throws RedisCacheException
     */
    public function initTags(array $tags) : void
    {
        if (!$tags) {
            return;
        }

        $tagsToSave = [];
        /** @var TagStructure $tagStructure */
        foreach ($tags as $tagStructure) {
            if (!$tagStructure instanceof TagStructure) {
                continue;
            }

            $tagsToSave[$tagStructure->getName()] = (string)$tagStructure;
        }

        if (!$this->getCacheConnect()->msetnx($tagsToSave)) {
            throw new RedisOperationException('Операция инициализации тегов не удалась.');
        }
    }

    /**
     * @param array $tags
     *
     * @return TagStructure[]
     *
     * @throws RedisCacheException
     */
    public function getTagsData(array $tags) : array
    {
        $result = [];
        foreach ($this->getCacheConnect()->mget($tags) as $key => $value) {
            $value = \json_decode($value, true);
            if (!$value) {
                continue;
            }

            $result[$tags[$key]] = new TagStructure($value);
            $result[$tags[$key]]->setName($tags[$key]);
        }

        return $result;
    }

    /**
     * @param string $key
     * @param CacheStructure $value
     * @param int $ttl
     *
     * @throws RedisCacheException
     */
    public function set(string $key, CacheStructure $value, int $ttl = null) : void
    {
        $ttl = $ttl ?? (int)$this->redis->getTimeout();
        if (!$this->getCacheConnect()->set($key, (string)$value, $ttl)) {
            throw new RedisOperationException('Операция записи по ключу не удалась.');
        }
    }

    /**
     * @param string $key
     *
     * @throws RedisCacheException
     * @throws RedisOperationException
     */
    public function delete(string $key) : void
    {
        if (!$this->getCacheConnect()->unlink($key)) {
            throw new RedisOperationException('Операция удаления по ключу не удалась.');
        }
    }
}
