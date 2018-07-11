<?php

namespace avtomon;

/**
 * Класс ошибок
 *
 * Class AbstractCacheItemException
 * @package avtomon
 */
class AbstractCacheItemException extends CustomException
{
}

/**
 * Базовый класс кэширования
 *
 * Class AbstractCacheItem
 * @package avtomon
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
        'solt' => '',
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
    protected $value = self::CACHE_STRUCTURE;

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
     * @throws AbstractCacheItemException
     * @throws \ReflectionException
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
            throw new AbstractCacheItemException('Текст запроса пуст');
        }

        $this->request = $request;
        $this->params = $params;
        $this->cacheConnect = $cacheConnect;
    }

    /**
     * Установить настройки объекта
     *
     * @param array $settings - массив настроек
     *
     * @throws \ReflectionException
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
     * @throws AbstractCacheItemException
     */
    public function setCacheConnect($cacheConnect): void
    {
        if (!($cacheConnect instanceof \Redis) && !($cacheConnect instanceof \Memcached)) {
            throw new AbstractCacheItemException('В качестве кэша можно использовать только Redis или Memcached');
        }

        $this->cacheConnect = $cacheConnect;
    }

    /**
     * Инициализация заданного массива тегов
     *
     * @throws AbstractCacheItemException
     */
    public function initTags(): void
    {
        if ($this->tags && !$this->cacheConnect) {
            throw new AbstractCacheItemException('Подключение к хранилищу кэшей отсутствует');
        }

        foreach ($this->tags as &$tag) {
            if (!$this->cacheConnect->set($tag, time(), $this->tagTtl)) {
                throw new AbstractCacheItemException('Не удалось установить значение тега');
            }
        }

        unset($tag);
    }

    /**
     * Установка тегов
     *
     * @param array $tags - массив тегов
     */
    protected function setTags(array $tags = []): void
    {
        $this->tags = $tags;
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
        if (!\in_array(\gettype($hashFunc), ['callable', 'string'], true)) {
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
        if (!\in_array(\gettype($serializeFunc), ['callable', 'string'], true)) {
            throw new AbstractCacheItemException('Формат передачи функции сериализации неверен');
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
            $this->key = ($this->hashFunc)($this->request . ($this->paramSerializeFunc)($this->params) . $this->solt);
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
    public function get()
    {
        if (!$this->cacheConnect) {
            throw new AbstractCacheItemException('Подключение к хранилищу кэшей отсутствует');
        }

        for ($i = 0; $i < $this->tryCount; $i++) {
            $this->value = $this->cacheConnect->get($this->getKey());

            if ($this->value === $this->lockValue) {
                usleep($this->tryDelay);
                continue;
            }

            $this->value = json_decode($this->value, true);
            if ($this->value === null || array_diff_key($this->value, self::CACHE_STRUCTURE)) {
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
     * Cохранение значение в кэше
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

        unset($value);

        $toSave['data'] = $data->getArrayResult() ?? $data->getStringResult();
        $toSave['time'] = time();

        if (!$this->cacheConnect->set($this->getKey(), json_encode($toSave, JSON_UNESCAPED_UNICODE), $this->ttl)) {
            return false;
        }

        $this->data = $data;
        $this->value = $toSave;

        return true;
    }

    /**
     * Удаление элемента кэша
     *
     * @return bool
     */
    public function delete(): bool
    {
        if (!$this->cacheConnect->delete($this->getKey())) {
            return false;
        }

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

        if (!$this->cacheConnect->set($this->getKey(), $this->lockValue, $this->ttl)) {
            return false;
        }

        $this->value = $this->lockValue;

        return true;
    }



}