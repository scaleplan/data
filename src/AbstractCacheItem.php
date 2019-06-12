<?php

namespace Scaleplan\Data;

use Scaleplan\Data\Cache\CacheInterface;
use Scaleplan\Data\Cache\MemcachedCache;
use Scaleplan\Data\Cache\RedisCache;
use Scaleplan\Data\Exceptions\DataException;
use Scaleplan\Data\Exceptions\ValidationException;
use Scaleplan\InitTrait\InitTrait;
use Scaleplan\Result\Interfaces\DbResultInterface;
use Scaleplan\Result\Interfaces\ResultInterface;

/**
 * Базовый класс кэширования
 *
 * Class AbstractCacheItem
 *
 * @package Scaleplan\Data
 */
abstract class AbstractCacheItem
{
    public const ID_FIELD = 'id';

    /**
     * Трейт инициализации
     */
    use InitTrait;

    /**
     * @var bool
     */
    protected $pconnect = true;

    /**
     * Текст запроса
     *
     * @var string
     */
    protected $request = '';

    /**
     * Параметры запроса
     *
     * @var array
     */
    protected $params = [];

    /**
     * Значение элемента кэша обозначающее блокировку
     *
     * @var string
     */
    protected $lockValue = '906a58a0aac5281e89718496686bb322';

    /**
     * Количество поппыток получения значения элемента кэша
     *
     * @var int
     */
    protected $tryCount = 3;

    /**
     * Соль хэширования ключа кэша
     *
     * @var string
     */
    protected $salt = '';

    /**
     * @var string|callable
     */
    protected $hashFunc = 'md5';

    /**
     * Функция сериализация параметров запроса
     *
     * @var null
     */
    protected $paramSerializeFunc = 'serialize';

    /**
     * Теги элемента кэша
     *
     * @var array
     */
    protected $tags = [];

    /**
     * @var string
     */
    protected $idTag = '';

    /**
     * @var int
     */
    protected $maxId = 0;

    /**
     * @var int
     */
    protected $minId = 0;

    /**
     * Временной интервал между двумя последовательными попытками получить значение элемента кэша
     *
     * @var int
     */
    protected $tryDelay = 10000;

    /**
     * @var CacheStructure
     */
    protected $value;

    /**
     * @var string
     */
    protected $cacheDbName = '';

    /**
     * @var string
     */
    protected $key;

    /**
     * Конструктор
     *
     * @param string $request - текст запроса
     * @param array $params - параметры запроса
     * @param array $settings - настройки
     *
     * @throws ValidationException
     */
    protected function __construct(
        string $request,
        array $params = [],
        array $settings = []
    )
    {
        $this->initObject($settings);

        if (!$request) {
            throw new ValidationException('Текст запроса пуст');
        }

        $this->request = $request;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getCacheDbName() : string
    {
        return $this->cacheDbName;
    }

    /**
     * @param string|null $cacheDbName
     */
    public function setCacheDbName(?string $cacheDbName) : void
    {
        $this->cacheDbName = $cacheDbName;
    }

    /**
     * @param bool $pconnect
     */
    public function setPconnect(bool $pconnect) : void
    {
        $this->pconnect = $pconnect;
    }

    /**
     * @param int $tryCount
     */
    public function setTryCount(int $tryCount) : void
    {
        $this->tryCount = $tryCount;
    }

    /**
     * @param CacheStructure $value
     */
    public function setValue(CacheStructure $value) : void
    {
        $this->value = $value;
    }

    /**
     * @param int $maxId
     */
    public function setMaxId(int $maxId) : void
    {
        $this->maxId = $maxId;
    }

    /**
     * @param int $minId
     */
    public function setMinId(int $minId) : void
    {
        $this->minId = $minId;
    }

    /**
     * @param string $idTag
     */
    public function setIdTag(string $idTag) : void
    {
        $this->idTag = $idTag;
    }

    /**
     * Установить настройки объекта
     *
     * @param array $settings - массив настроек
     */
    public function setSettings(array $settings) : void
    {
        $this->initObject($settings);
    }

    /**
     * @return MemcachedCache|RedisCache
     *
     * @throws DataException
     */
    protected function getCacheConnect() : CacheInterface
    {
        static $cacheConnect;
        if (!$cacheConnect) {
            if (!$cacheType = getenv('CACHE_TYPE')) {
                throw new DataException('Не задан тип кэша.');
            }

            switch ($cacheType) {
                case 'redis':
                    $cacheConnect = new RedisCache($this->pconnect);
                    break;

                case 'memcached':
                    $cacheConnect = new MemcachedCache($this->pconnect);
                    break;

                default:
                    throw new DataException("Тип кэша $cacheType не поддерживается.");
            }
        }
        $cacheConnect->selectDatabase($this->cacheDbName);

        return $cacheConnect;
    }

    /**
     * Инициализация заданного массива тегов
     *
     * @param DbResultInterface $result
     *
     * @throws DataException
     * @throws Exceptions\MemcachedCacheException
     * @throws Exceptions\MemcachedOperationException
     * @throws Exceptions\RedisCacheException
     */
    public function initTags(DbResultInterface $result) : void
    {
        $tagsToSave = [];
        foreach ($this->tags as $tagName) {
            $tagStructure = new TagStructure();
            $tagStructure->setName($tagName);
            $tagStructure->setTime(time());
            if ($this->idTag && $tagName === $this->idTag) {
                /** @var TagStructure $tag */
                $tag = $this->getCacheConnect()->getTagsData([$tagName])[0];

                $max = max(array_column($result->getArrayResult(), static::ID_FIELD));
                if ($tag->getMaxId() < $max) {
                    $tagStructure->setMaxId($max);
                }

                $min = min(array_column($result->getArrayResult(), static::ID_FIELD));
                if ($tag->getMinId() > $min) {
                    $tagStructure->setMinId($max);
                }
            }
            $tagsToSave[$tagName] = $tagStructure;

        }

        $this->getCacheConnect()->initTags($tagsToSave);
    }

    /**
     * Установка тегов
     *
     * @param array $tags - массив тегов
     */
    public function setTags(array $tags = []) : void
    {
        $this->tags = $tags;
    }

    /**
     * Установить функцию хэширования ключа
     *
     * @param string|callable $hashFunc - функция хэширования
     *
     * @throws DataException
     */
    public function setHashFunc($hashFunc) : void
    {
        if (!\in_array(\gettype($hashFunc), ['callable', 'string'], true)) {
            throw new ValidationException('Формат передачи функции хэширования неверен');
        }

        $this->hashFunc = $hashFunc;
    }

    /**
     * Установить функцию сериализации параметров запроса
     *
     * @param callable|string $serializeFunc - функция сериализации
     *
     * @throws DataException
     */
    public function setParamSerializeFunc($serializeFunc) : void
    {
        if (!\in_array(\gettype($serializeFunc), ['callable', 'string'], true)) {
            throw new ValidationException('Формат передачи функции сериализации неверен');
        }

        $this->paramSerializeFunc = $serializeFunc;
    }

    /**
     * Функция получения ключа элемента кэша
     *
     * @return string
     */
    protected function getKey() : string
    {
        if (!$this->key) {
            $this->key = ($this->hashFunc)($this->request . ($this->paramSerializeFunc)($this->params) . $this->salt);
        }

        return $this->key;
    }

    /**
     * @param CacheStructure $value
     *
     * @return bool
     *
     * @throws DataException
     * @throws Exceptions\MemcachedCacheException
     * @throws Exceptions\RedisCacheException
     */
    public function validate(CacheStructure $value) : bool
    {
        if (!$value->getTime()) {
            return false;
        }

        $tags = $this->getCacheConnect()->getTagsData($value->getTags());
        $tagsData = array_map(static function (TagStructure $item) {
            return $item->toArray();
        }, $tags);

        $times = array_merge(array_column($tagsData, 'time'), [$value->getTime()]);
        rsort($times);
        if ($times[0] === $value->getTime()) {
            return true;
        }

        if ($this->idTag
            && !empty($tags[$this->idTag])
            && $times[1] === $value->getTime()
            && $tags[$this->idTag]->getTime() === $times[0])
        {
            if ($tags[$this->idTag]->getMinId() > $value->getMaxId()
                || $tags[$this->idTag]->getMaxId() < $value->getMinId()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Получить значение элемента кэша
     *
     * @return CacheStructure
     *
     * @throws DataException
     */
    public function get()
    {
        if (!$this->value) {
            for ($i = 0; $i < $this->tryCount; $i++) {
                $this->setValue($this->getCacheConnect()->get($this->getKey()));

                if ($this->value->getData() === $this->lockValue) {
                    usleep($this->tryDelay);
                    $this->value->setData(null);
                    continue;
                }

                if (!$this->validate($this->value)) {
                    $this->value->setData(null);
                }

                break;
            }
        }

        return $this->value;
    }

    /**
     * Сохранение значение в кэше
     *
     * @param ResultInterface $data - значение для сохрания
     *
     * @throws DataException
     */
    public function set(ResultInterface $data) : void
    {
        $cacheData = new CacheStructure();
        $cacheData->setData($data->getResult());
        $cacheData->setTime(time());
        $cacheData->setTags($this->tags);
        if ($this->idTag && \in_array($this->idTag, $this->tags, true)) {
            $cacheData->setIdTag($this->idTag);
            if ($data instanceof DbResultInterface && !$this->minId) {
                $this->setMinId(min(array_column($data->getArrayResult(), static::ID_FIELD)));
            }
            $cacheData->setMinId($this->minId);

            if ($data instanceof DbResultInterface && !$this->minId) {
                $this->setMaxId(max(array_column($data->getArrayResult(), static::ID_FIELD)));
            }
            $cacheData->setMaxId($this->minId);
        }

        $this->getCacheConnect()->set($this->getKey(), $cacheData);

        $this->value = $cacheData;
    }

    /**
     * Удаление элемента кэша
     *
     * @throws DataException
     * @throws Exceptions\MemcachedCacheException
     * @throws Exceptions\RedisCacheException
     * @throws Exceptions\RedisOperationException
     */
    public function delete() : void
    {
        $this->getCacheConnect()->delete($this->getKey());
        $this->value = null;
    }

    /**
     * Установить блокировку по ключу
     *
     * @throws DataException
     */
    public function setLock() : void
    {
        $cacheData = new CacheStructure();
        $cacheData->setData($this->lockValue);
        $this->getCacheConnect()->set($this->getKey(), $cacheData);
        $this->value = $cacheData;
    }
}
