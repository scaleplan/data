<?php

namespace Scaleplan\Data;

use Scaleplan\Data\Exceptions\CacheConnectException;
use Scaleplan\Data\Exceptions\DataException;
use Scaleplan\Data\Exceptions\ValidationException;
use Scaleplan\InitTrait\InitTrait;
use Scaleplan\Result\AbstractResult;

/**
 * Базовый класс кэширования
 *
 * Class AbstractCacheItem
 *
 * @package Scaleplan\Data
 */
abstract class AbstractCacheItem
{
    /**
     * Трейт инициализации
     */
    use InitTrait;

    /**
     * Структура элемента кэша по умолчанию
     */
    protected const CACHE_STRUCTURE = [
        'data' => '',
        'time' => 0,
        'tags' => []
    ];

    /**
     * Настройки элемента кэша
     *
     * @var array
     */
    protected static $settings = [
        'tagTtl' => 7200,
        'salt' => '',
        'hashFunc' => 'md5',
        'paramSerializeFunc' => 'serialize',
        'ttl' => 3600,
        'lockValue' => '906a58a0aac5281e89718496686bb322',
        'tryCount' => 5,
        'tryDelay' => 10000
    ];

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
     * Данные из езультата запроса или значение для сохранения в кэше
     *
     * @var null|AbstractResult
     */
    protected $data;

    /**
     * Значение сохраненное в кэше
     *
     * @var array
     */
    protected $value;

    /**
     * Подключение к хранилицу кэшей
     *
     * @var \Memcached|\Redis|null
     */
    protected $cacheConnect;

    /**
     * Время жизни тега
     *
     * @var int
     */
    protected $tagTtl = 7200;

    /**
     * Время жизни элемента кэша
     *
     * @var int
     */
    protected $ttl = 3600;

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
    protected $tryCount = 1;

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
     * Ключ кэша
     *
     * @var string
     */
    protected $key = '';

    /**
     * Теги элемента кэша
     *
     * @var array
     */
    protected $tags = [];

    /**
     * Временной интервал между двумя последовательными попытками получить значение элемента кэша
     *
     * @var int
     */
    protected $tryDelay = 10000;

    /**
     * Конструктор
     *
     * @param string $request - текст запроса
     * @param array $params - параметры запроса
     * @param \Memcached|\Redis|null $cacheConnect - подключение к хранилицу кэшей
     * @param array $settings - настройки
     *
     * @throws ValidationException
     */
    protected function __construct(
        string $request,
        array $params = [],
        $cacheConnect = null,
        array $settings = []
    )
    {
        $this->initObject($settings);

        if (!$request) {
            throw new ValidationException('Текст запроса пуст');
        }

        $this->request = $request;
        $this->params = $params;
        $this->cacheConnect = $cacheConnect;
    }

    /**
     * Установить настройки объекта
     *
     * @param array $settings - массив настроек
     */
    public function setSettings(array $settings): void
    {
        $this->initObject($settings);
    }

    /**
     * Установить подключение к кэширующему хранилищу
     *
     * @param \Memcached|\Redis $cacheConnect - объект подключения
     *
     * @throws DataException
     */
    public function setCacheConnect($cacheConnect): void
    {
        if (!($cacheConnect instanceof \Redis) && !($cacheConnect instanceof \Memcached)) {
            throw new CacheConnectException(
                'В качестве кэша можно использовать только Redis или Memcached'
            );
        }

        $this->cacheConnect = $cacheConnect;
    }

    /**
     * Инициализация заданного массива тегов
     *
     * @throws DataException
     */
    public function initTags(): void
    {
        if ($this->tags && !$this->cacheConnect) {
            throw new CacheConnectException();
        }

        if ($this->cacheConnect instanceof \Redis) {
            $this->cacheConnect->mset(array_fill_keys($this->tags, time()));
            return;
        }

        foreach ($this->tags as &$tag) {
            if (!$this->cacheConnect->set($tag, time(), $this->tagTtl)) {
                throw new DataException('Не удалось установить значение тега');
            }
        }

        unset($tag);
    }

    /**
     * Установка тегов
     *
     * @param array $tags - массив тегов
     */
    public function setTags(array $tags = []): void
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
    public function setHashFunc($hashFunc): void
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
    public function setParamSerializeFunc($serializeFunc): void
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
    protected function getKey(): string
    {
        if (!$this->key) {
            $this->key = ($this->hashFunc)($this->request . ($this->paramSerializeFunc)($this->params) . $this->salt);
        }

        return $this->key;
    }

    /**
     * Возвращает массив времен актуальности тегов асоциированных с запросом
     *
     * @return array
     *
     * @throws DataException
     */
    protected function getTagsTimes(): array
    {
        if (!$this->cacheConnect) {
            throw new CacheConnectException();
        }

        if ($this->cacheConnect instanceof \Redis) {
            return $this->cacheConnect->mget($this->tags);
        }

        return array_map(function ($tag) {
            return (int) $this->cacheConnect->get($tag);
        }, $this->tags);
    }

    /**
     * Получить значение элемента кэша
     *
     * @return array|null
     *
     * @throws DataException
     */
    public function get()
    {
        if (!$this->cacheConnect) {
            throw new CacheConnectException();
        }

        for ($i = 0; $i < $this->tryCount; $i++) {
            $this->value = $this->cacheConnect->get($this->getKey());

            if ($this->value === $this->lockValue) {
                usleep($this->tryDelay);
                continue;
            }

            $this->value = json_decode($this->value, true);
            if ($this->value === null || array_diff_key($this->value, static::CACHE_STRUCTURE)) {
                return null;
            }

            if (max(array_merge($this->getTagsTimes(), [$this->value['time']])) !== $this->value['time']) {
                return null;
            }

            return $this->data = $this->value['data'] ?? null;
        }

        return null;
    }

    /**
     * Сохранение значение в кэше
     *
     * @param AbstractResult $data - значение для сохрания
     *
     * @return bool
     *
     * @throws DataException
     */
    public function set(AbstractResult $data): bool
    {
        if (!$this->cacheConnect) {
            throw new CacheConnectException();
        }

        $toSave = static::CACHE_STRUCTURE;
        foreach ($toSave as $key => &$value) {
            $value = $this->$key ?? $value;
        }

        unset($value);

        $toSave['data'] = $data->getResult();
        $toSave['time'] = time();

        if (!$this->cacheConnect->set($this->getKey(), json_encode($toSave, JSON_UNESCAPED_UNICODE), $this->ttl)) {
            return false;
        }

        $this->data = $data;
        $this->value = $toSave;

        return true;
    }

    /**
     * Асинхронное удаление элемента кэша
     *
     * @return bool
     */
    public function delete(): bool
    {
        $func = $this->cacheConnect instanceof \Redis ? 'unlink' : 'delete';
        if (!$this->cacheConnect->$func($this->getKey())) {
            return false;
        }

        return true;
    }

    /**
     * Установить блокировку по ключу
     *
     * @return bool
     *
     * @throws DataException
     */
    public function setLock(): bool
    {
        if (!$this->cacheConnect) {
            throw new CacheConnectException();
        }

        if (!$this->cacheConnect->set($this->getKey(), $this->lockValue, $this->ttl)) {
            return false;
        }

        $this->value = $this->lockValue;

        return true;
    }
}
