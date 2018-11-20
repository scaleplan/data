<?php

namespace Scaleplan\Data;

use Scaleplan\CachePDO\CachePDO;
use Scaleplan\Data\Exceptions\CacheException;
use Scaleplan\InitTrait\InitTrait;
use Scaleplan\Result\DbResult;
use Scaleplan\Result\HTMLResult;

/**
 * Основной класс получения данных
 *
 * Class DataStory
 *
 * @package Scaleplan\Data
 */
class Data
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
        'dbConnect'    => null,
        'cacheConnect' => null,
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
     * @var null|CachePDO
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
     * Путь к файлу, по которому будет проверяться акутуальность кэша
     *
     * @var string
     */
    protected $verifyingFilePath = '';

    /**
     * Префикс имен результирующих полей
     *
     * @var string
     */
    protected $prefix = '';

    /**
     * Создать или вернуть инстранс класса
     *
     * @param string $request - текст запроса
     * @param array $params - параметры запроса
     * @param array $settings - дополниетельные настройки
     *
     * @return Data
     *
     * @throws \ReflectionException
     */
    public static function create(string $request, array $params = [], array $settings = []) : Data
    {
        $key = md5($request . serialize($params));
        if (empty(static::$instances[$key])) {
            static::$instances[$key] = new static($request, $params, $settings);
        }

        return static::$instances[$key];
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
    public function setIsModifying(bool $flag = true) : void
    {
        $this->requestSettings['isModifying'] = $flag;
    }

    /**
     * Установить параметры запроса
     *
     * @param array $params - параметры
     */
    public function setParams(array $params) : void
    {
        $this->params = $params;
    }

    /**
     * Установить посдключение к кэшу
     *
     * @param null|\Redis|\Memcached $cacheConnect - подключение к кэшу
     */
    public function setCacheConnect($cacheConnect) : void
    {
        $this->cacheConnect = $cacheConnect;
    }

    /**
     * Установить подключение к РБД
     *
     * @param CachePDO|null $dbConnect
     */
    public function setDbConnect(?CachePDO $dbConnect) : void
    {
        $this->dbConnect = $dbConnect;
    }

    /**
     * @param string $verifyingFilePath
     */
    public function setVerifyingFilePath(string $verifyingFilePath) : void
    {
        $this->verifyingFilePath = $verifyingFilePath;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix(string $prefix) : void
    {
        $this->prefix = $prefix;
    }

    /**
     * Вернуть объект кэша запросов
     *
     * @return CacheQuery
     *
     * @throws Exceptions\DataException
     * @throws \ReflectionException
     */
    protected function getCacheQuery() : CacheQuery
    {
        if (!$this->cacheQuery) {
            $this->cacheQuery = new CacheQuery(
                $this->dbConnect,
                $this->request,
                $this->params,
                $this->cacheConnect,
                $this->requestSettings
            );
        }

        return $this->cacheQuery;
    }

    /**
     * Вернуть объект кэша страниц
     *
     * @return CacheHtml
     *
     * @throws Exceptions\DataException
     * @throws Exceptions\ValidationException
     * @throws \ReflectionException
     */
    protected function getCacheHtml() : CacheHtml
    {
        if (!$this->cacheHtml) {
            $this->cacheHtml = new CacheHtml(
                $this->request,
                $this->params,
                $this->cacheConnect,
                $this->requestSettings
            );
        }

        return $this->cacheHtml;
    }

    /**
     * Вернуть результат запроса к РБД
     *
     * @return DbResult
     *
     * @throws Exceptions\DataException
     * @throws \ReflectionException
     * @throws \Scaleplan\Result\Exceptions\ResultException
     */
    public function getValue() : DbResult
    {
        $getQuery = function () {
            return (new Query($this->request, $this->dbConnect, $this->params))->execute($this->prefix);
        };

        if ($this->getCacheQuery()->isModifying()) {
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
     * @throws Exceptions\DataException
     * @throws \ReflectionException
     */
    public function deleteValue() : bool
    {
        return $this->getCacheQuery()->delete();
    }

    /**
     * Вернуть HTML
     *
     * @param $userId
     *
     * @return HTMLResult
     * @throws Exceptions\DataException
     * @throws Exceptions\ValidationException
     * @throws \ReflectionException
     */
    public function getHtml($userId) : HTMLResult
    {
        $cacheHtml = $this->getCacheHtml();
        $cacheHtml->setCheckFile($this->verifyingFilePath);
        $cacheHtml->setUserId($userId);
        return $this->getCacheHtml()->get();
    }

    /**
     * Сохранить к кэше HTML-страницу
     *
     * @param HTMLResult $html - HTML
     * @param array|null $tags - теги
     *
     * @throws Exceptions\DataException
     * @throws Exceptions\ValidationException
     * @throws \ReflectionException
     */
    public function setHtml(HTMLResult $html, array $tags = []) : void
    {
        $cacheHtml = $this->getCacheHtml();
        $cacheHtml->setTags($tags);
        if (!$cacheHtml->set($html)) {
            throw new CacheException('Не удалось сохранить HTML в кэше');
        }
    }

    /**
     * Удаление элемента кэша страниц
     *
     * @return bool
     *
     * @throws Exceptions\DataException
     * @throws Exceptions\ValidationException
     * @throws \ReflectionException
     */
    public function deleteHtml() : bool
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
     * @return null|DbResult
     * @throws Exceptions\DataException
     * @throws \ReflectionException
     * @throws \Scaleplan\Result\Exceptions\ResultException
     */
    public static function execQuery(string $request, array $params = [], array $settings = []) : ?DbResult
    {
        return static::create($request, $params, $settings)->getValue();
    }

    /**
     * @param $userId
     *
     * @return string
     *
     * @throws Exceptions\DataException
     * @throws Exceptions\ValidationException
     * @throws \ReflectionException
     * @throws \Scaleplan\Result\Exceptions\ResultException
     */
    public function getCache($userId) : string
    {
        if ($this->dbConnect) {
            return (string)$this->getValue();
        }

        return (string)$this->getHtml($userId);
    }
}