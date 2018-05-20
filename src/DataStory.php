<?php

namespace avtomon;

class DataStoryException extends CustomException
{
}

class DataStory
{
    /**
     * Трейт инициализации настроек
     */
    use InitTrait;

    /**
     * Настройки класса
     *
     * @var array
     */
    protected static $settings = [
        'dbConnect' => null,
        'cacheConnect' => null
    ];

    /**
     * Доступный инстансы класса
     *
     * @var array
     */
    protected static $instances = [];

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
     * Подключение к РБД
     *
     * @var null|_PDO
     */
    protected $dbConnect = null;

    /**
     * Подключение к хранилищу кэшей
     *
     * @var null|\Redis|\Memcached
     */
    protected $cacheConnect = null;

    /**
     * Объект кэша запросов
     *
     * @var null|CacheQuery
     */
    protected $cacheQuery = null;

    /**
     * Объект кэша страниц
     *
     * @var null|CacheHtml
     */
    protected $cacheHtml = null;

    /**
     * Свойства запроса
     *
     * @var array
     */
    protected $requestSettings = [];

    /**
     * Создать или вернуть инстранс класса
     *
     * @param string $request - текст запроса
     * @param array $params - параметры запроса
     *
     * @return DataStory
     */
    public static function create(string $request, array $params = [], array $settings = []): DataStory
    {
        $key = md5($request . serialize($params));
        if (empty(self::$instances[$key])) {
            self::$instances[$key] = new DataStory($request, $params, $settings);
        }

        return self::$instances[$key];
    }

    /**
     * Конструктор
     *
     * @param string $request - текст запроса
     * @param array $params - параметры запроса
     *
     * @throws DataStoryException
     */
    protected function __construct(string $request, array $params = [], array $settings = [])
    {
        $this->requestSettings = $this->initObject($settings);

        $this->request = $request;
        $this->params = $params;
    }

    /**
     * Установить параметры запроса
     *
     * @param array $params - параметры
     */
    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    /**
     * Установить посдключение к кэшу
     *
     * @param null|\Redis|\Memcached $cacheConnect - подключение к кэшу
     *
     * @throws DataStoryException
     */
    public function setCacheConnect($cacheConnect): void
    {
        $this->cacheConnect = $cacheConnect;
    }

    /**
     * Установить подключение к РБД
     *
     * @param _PDO|null $dbConnect
     */
    public function setDbConnect(?_PDO $dbConnect): void
    {
        $this->dbConnect = $dbConnect;
    }

    /**
     * Вернуть объект кэша запросов
     *
     * @return CacheQuery
     */
    protected function getCacheQuery(): CacheQuery
    {
        if (!$this->cacheQuery) {
            $this->cacheQuery = new CacheQuery($this->dbConnect, $this->request, $this->params, $this->cacheConnect, $this->requestSettings);
        }

        return $this->cacheQuery;
    }

    /**
     * Вернуть объект кэша страниц
     *
     * @return CacheHtml
     */
    protected function getCacheHtml(): CacheHtml
    {
        if (!$this->cacheHtml) {
            $this->cacheHtml = new CacheHtml($this->request, $this->params, $this->cacheConnect, $this->requestSettings);
        }

        return $this->cacheHtml;
    }

    /**
     * Вернуть результат запроса к РБД
     *
     * @param string $prefix - префикс имен результирующих полей
     *
     * @return DbResultItem|null
     *
     * @throws AbstractCacheItemException
     * @throws DataStoryException
     * @throws QueryException
     */
    public function getValue(string $prefix = ''): ?DbResultItem
    {
        $getQuery = function () use ($prefix) {
            return (new Query($this->dbConnect, $this->request, $this->params))->execute($prefix);
        };

        if ($this->getCacheQuery()->getIsModifying()) {
            $result = $getQuery();
            $this->getCacheQuery()->initTags();
            return $result;
        }

        $result = $this->cacheQuery->get();
        if (is_null($result->getResult())) {
            $result = $getQuery();
            $this->getCacheQuery()->set($result);
        }

        return $result;
    }

    /**
     * Удаление элемента кэша запросов к БД
     *
     * @return bool
     *
     * @throws Exception
     */
    public function deleteValue(): bool
    {
        return $this->getCacheQuery()->delete();
    }

    /**
     * Вернуть HTML
     *
     * @return HTMLResultItem|null
     *
     * @throws AbstractCacheItemException
     * @throws DataStoryException
     */
    public function getHtml(string $verifyingFilePath = ''): ?HTMLResultItem
    {
        $cacheHtml = $this->getCacheHtml();
        $cacheHtml->setCheckFile($verifyingFilePath);
        return $this->getCacheHtml()->get();
    }

    /**
     * Сохранить к кэше HTML-страницу
     *
     * @param HTMLResultItem $html - HTML
     * @param array|null $tags - теги
     *
     * @throws AbstractCacheItemException
     * @throws DataStoryException
     */
    public function setHtml(HTMLResultItem $html, array $tags = null): void
    {
        $cacheHtml = $this->getCacheHtml();
        $cacheHtml->setTags($tags);
        if (!$cacheHtml->set($html)) {
            throw new DataStoryException('Не удалось сохранить HTML в кэше');
        }
    }

    /**
     * Удаление элемента кэша страниц
     *
     * @return bool
     *
     * @throws Exception
     */
    public function deleteHtml(): bool
    {
        return $this->getCacheHtml()->delete();
    }

    /**
     * Создать объект запроса и выполнить его
     *
     * @param string $request - текст запроса
     * @param array $params - параметры запроса
     *
     * @return DbResultItem|null
     *
     * @throws AbstractCacheItemException
     * @throws DataStoryException
     * @throws QueryException
     */
    public static function execQuery(string $request, array $params = [], array $settings = []): ?DbResultItem
    {
        return self::create($request, $params, $settings)->getValue();
    }
}