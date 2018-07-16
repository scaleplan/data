<?php

namespace avtomon;

/**
 * Класс ошибок
 *
 * Class DataStoryException
 * @package avtomon
 */
class DataStoryException extends CustomException
{
}

/**
 * Основной класс получения данных
 *
 * Class DataStory
 * @package avtomon
 */
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
    protected $dbConnect;

    /**
     * Подключение к хранилищу кэшей
     *
     * @var null|\Redis|\Memcached
     */
    protected $cacheConnect;

    /**
     * Объект кэша запросов
     *
     * @var null|CacheQuery
     */
    protected $cacheQuery;

    /**
     * Объект кэша страниц
     *
     * @var null|CacheHtml
     */
    protected $cacheHtml;

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
     * @param array $settings - дополниетельные настройки
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
     * @param array $settings - настройки
     *
     * @throws \ReflectionException
     */
    protected function __construct(string $request, array $params = [], array $settings = [])
    {
        $this->requestSettings = $this->initObject($settings);

        $this->request = $request;
        $this->params = $params;
    }

    /**
     * Установить тип запроса: изменяющий (true) или читающий (false)
     *
     * @param bool $flag
     */
    public function setIsModifying(bool $flag = true): void
    {
        $this->requestSettings['isModifying'] = $flag;
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
     *
     * @throws AbstractCacheItemException
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
     *
     * @throws AbstractCacheItemException
     * @throws CacheHtmlException
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
     * @return DbResultItem
     *
     * @throws AbstractCacheItemException
     * @throws DbResultItemException
     */
    public function getValue(string $prefix = ''): DbResultItem
    {
        $getQuery = function () use ($prefix) {
            return (new Query($this->dbConnect, $this->request, $this->params))->execute($prefix);
        };

        if ($this->getCacheQuery()->getIsModifying()) {
            $result = $getQuery();
            $this->getCacheQuery()->initTags();
            return $result;
        }

        $result = $this->getCacheQuery()->get();
        if ($result->getResult() === null) {
            $this->getCacheQuery()->setLock();
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
     * @throws AbstractCacheItemException
     */
    public function deleteValue(): bool
    {
        return $this->getCacheQuery()->delete();
    }

    /**
     * Вернуть HTML
     *
     * @param string $verifyingFilePath - путь к файлу, по которому будет проверяться акутуальность кэша
     *
     * @return HTMLResultItem
     *
     * @throws AbstractCacheItemException
     * @throws CacheHtmlException
     */
    public function getHtml(string $verifyingFilePath = ''): HTMLResultItem
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
     * @throws CacheHtmlException
     * @throws DataStoryException
     */
    public function setHtml(HTMLResultItem $html, array $tags = []): void
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
     * @throws AbstractCacheItemException
     * @throws CacheHtmlException
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
     * @param array $settings - настройки
     *
     * @return DbResultItem|null
     *
     * @throws AbstractCacheItemException
     * @throws DbResultItemException
     */
    public static function execQuery(string $request, array $params = [], array $settings = []): ?DbResultItem
    {
        return self::create($request, $params, $settings)->getValue();
    }
}