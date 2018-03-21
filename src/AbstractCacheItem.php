<?php

namespace avtomon;

class AbstractCacheItemException extends \Exception
{
}

abstract class AbstractCacheItem
{
    use InitTrait;

    /**
     * Структура элемента кэша по умолчанию
     */
    const CACHE_STRUCTURE = [
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
        'solt' => '',
        'hashFunc' => 'md5',
        'paramSerializeFunc' => 'serialize',
        'ttl' => 3600,
        'lockValue' => '906a58a0aac5281e89718496686bb322',
        'tryCount' => 5
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
     * @var AbstractResult
     */
    protected $data = null;

    /**
     * Значение сохраненное в кэше
     *
     * @var array
     */
    protected $value = self::CACHE_STRUCTURE;

    /**
     * Подключение к хранилицу кэшей
     *
     * @var \Memcached|\Redis|null
     */
    protected $cacheConnect = null;

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
    protected $solt = '';

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
     * Конструктор
     *
     * @param string $request - текст запроса
     * @param array $params - параметры запроса
     * @param null $cacheConnect - подключение к хранилицу кэшей
     * @param array $settings|null - настройки
     *
     * @throws AbstractCacheItemException
     */
    protected function __construct(
        string $request,
        array $params = [],
        $cacheConnect = null,
        array $settings = null
    )
    {
        $this->initObject($settings ?? self::$settings);

        if (!$request) {
            throw new AbstractCacheItemException('Текст запроса пуст');
        }

        $this->request = $request;
        $this->params = $params;
        $this->cacheConnect = $cacheConnect;
    }

    /**
     * Установить настройки объекта
     *
     * @param array $settings
     */
    public function setSettings(array $settings): void
    {
        $this->initObject($settings);
    }

    /**
     * Установить подключение к кэширующему хранилищу
     *
     * @param \Memcached|\Redis $cacheConnect
     */
    public function cacheConnect($cacheConnect)
    {
        $this->cacheConnect = $cacheConnect;
    }

    /**
     * Инициализация заданного массива тегов
     *
     * @param array $tags - массив тегов
     *
     * @return bool
     *
     * @throws AbstractCacheItemException
     */
    protected function initTags(array $tags = []): void
    {
        if (!$this->cacheConnect) {
            throw new CacheItemException('Подключение к хранилищу кэшей отсутствует');
        }

        if (!$tags) {
            throw new AbstractCacheItemException('Теги отсутствуют');
        }

        foreach ($tags as &$tag) {
            if (!$this->cacheConnect->set($tag, time(), $this->tagTtl)) {
                throw new AbstractCacheItemException('Не удалось установить значение тега');
            }
        }

        unset($tag);
    }

    /**
     * Установить функцию хэширования ключа
     *
     * @param string|callable $hashFunc - функция хэширования
     *
     * @throws AbstractCacheItemException
     */
    public function setHashFunc($hashFunc): void
    {
        if (!in_array(gettype($hashFunc), ['callable', 'string'])) {
            throw new AbstractCacheItemException('Формат передачи функции хэширования неверен');
        }

        $this->hashFunc = $hashFunc;
    }

    /**
     * Установить функцию сериализации параметров запроса
     *
     * @param callable|string $serializeFunc - функция сериализации
     *
     * @throws AbstractCacheItemException
     */
    public function setParamSerializeFunc($serializeFunc): void
    {
        if (!in_array(gettype($serializeFunc), ['callable', 'string'])) {
            throw new AbstractCacheItemException('Формат передачи функции сериализации неверен');
        }

        $this->paramSerializeFunc = $serializeFunc;
    }

    /**
     * Функция получения ключа элемента кэша
     *
     * @return string
     *
     * @throws Exception
     */
    protected function getKey(): string
    {
        if (!$this->key) {
            $this->key = $this->hashFunc($this->request . $this->paramSerializeFunc($this->params) . $this->solt);
        }

        return $this->key;
    }

    /**
     * Возвращает массив времен актуальности тегов асоциированных с запросом
     *
     * @return array
     *
     * @throws AbstractCacheItemException
     */
    protected function getTagsTimes(): array
    {
        if (!$this->cacheConnect) {
            throw new AbstractCacheItemException('Подключение к хранилищу кэшей отсутствует');
        }

        return array_map(function ($tag) {
            return $this->cacheConnect->get($tag);
        }, $this->tags);
    }

    /**
     * Получить значение элемента кэша
     *
     * @return array|null
     *
     * @throws AbstractCacheItemException
     */
    public function get(): ?array
    {
        if (!$this->cacheConnect) {
            throw new AbstractCacheItemException('Подключение к хранилищу кэшей отсутствует');
        }

        for ($i = 0; $i < $this->tryCount; $i++) {
            $this->value = $this->cacheConnect->get($this->key);

            if ($this->value === $this->lockValue) {
                continue;
            }

            if (($this->value = json_decode($this->value, true)) === null || !array_diff_key($this->value, self::CACHE_STRUCTURE)) {
                return null;
            }

            if ((int) $this->value['time'] < time() && min(array_merge($tagsTimes, [$this->value['time']])) !== $this->value['time']) {
                return null;
            }

            return $this->value;
        }

        return null;
    }

    /**
     * Cсохранение значение в кэше
     *
     * @param AbstractResult $data - значение для сохрания
     *
     * @return bool
     *
     * @throws AbstractCacheItemException
     */
    public function set(AbstractResult $data): bool
    {
        if (!$this->cacheConnect) {
            throw new AbstractCacheItemException('Подключение к хранилищу кэшей отсутствует');
        }

        $toSave = self::CACHE_STRUCTURE;
        foreach ($toSave as $key => &$value) {
            $value = $this->$key ?? $value;
        }

        $toSave['data'] = $data->getStringResult();

        if ($this->cacheConnect->set($this->key, json_encode($toSave, JSON_UNESCAPED_UNICODE), $this->ttl)) {
            return false;
        }

        $this->data = $data;
        $this->value = $toSave;

        return true;
    }

    /**
     * Установить блокировку по ключу
     *
     * @return bool
     *
     * @throws AbstractCacheItemException
     */
    public function setLock(): bool
    {
        if (!$this->cacheConnect) {
            throw new AbstractCacheItemException('Подключение к хранилищу кэшей отсутствует');
        }

        if (!$this->cacheConnect->set($this->key, $this->lockValue)) {
            return false;
        }

        $this->value = $this->lockValue;
    }



}