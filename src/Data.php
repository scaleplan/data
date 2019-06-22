<?php

namespace Scaleplan\Data;

use Scaleplan\Data\Interfaces\CacheInterface;
use Scaleplan\Data\Interfaces\DataInterface;
use Scaleplan\Db\Interfaces\DbInterface;
use Scaleplan\InitTrait\InitTrait;
use Scaleplan\Result\HTMLResult;
use Scaleplan\Result\Interfaces\DbResultInterface;

/**
 * Основной класс получения данных
 *
 * Class DataStory
 *
 * @package Scaleplan\Data
 */
class Data implements CacheInterface, DataInterface
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
     * @var null|DbInterface
     */
    protected $dbConnect;

    /**
     * Объект кэша запросов
     *
     * @var null|QueryCache
     */
    protected $queryCache;

    /**
     * Объект кэша страниц
     *
     * @var null|HtmlCache
     */
    protected $htmlCache;

    /**
     * Префикс имен результирующих полей
     *
     * @var string|null
     */
    protected $prefix;

    /**
     * @var array|null
     */
    protected $tags;

    /**
     * Свойства запроса
     *
     * @var array
     */
    protected $requestSettings = [];

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
     * @var string
     */
    protected $cacheDbName;

    /**
     * @var bool
     */
    protected $cacheEnable = true;

    /**
     * Создать или вернуть инстранс класса
     *
     * @param string $request - текст запроса
     * @param array $params - параметры запроса
     * @param array $settings - дополниетельные настройки
     *
     * @return Data
     */
    public static function getInstance(string $request, array $params = [], array $settings = []) : Data
    {
        $key = md5($request . serialize($params));
        if (empty(static::$instances[$key])) {
            static::$instances[$key] = new static($request, $params, $settings);
        }

        return static::$instances[$key];
    }

    /**
     * @param bool $cacheEnable
     */
    public function setCacheEnable(bool $cacheEnable) : void
    {
        $this->cacheEnable = $cacheEnable;
    }

    /**
     * Конструктор
     *
     * @param string $request - текст запроса
     * @param array $params - параметры запроса
     * @param array $settings - настройки
     */
    protected function __construct(string $request, array $params = [], array $settings = [])
    {
        $this->requestSettings = $this->initObject($settings);

        $this->request = $request;
        $this->params = $params;
    }

    /**
     * @param string|null $cacheDbName
     */
    public function setCacheDbName(?string $cacheDbName) : void
    {
        $this->cacheDbName = $cacheDbName;
    }

    /**
     * Установить тип запроса: изменяющий (true) или читающий (false)
     *
     * @param bool $flag
     *
     * @throws Exceptions\ValidationException
     * @throws \ReflectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ContainerTypeNotSupportingException
     * @throws \Scaleplan\DependencyInjection\Exceptions\DependencyInjectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ParameterMustBeInterfaceNameOrClassNameException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ReturnTypeMustImplementsInterfaceException
     */
    public function setIsModifying(bool $flag = true) : void
    {
        $this->getQueryCache()->setIsModifying($flag);
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
     * Установить подключение к РБД
     *
     * @param DbInterface|null $dbConnect
     */
    public function setDbConnect(?DbInterface $dbConnect) : void
    {
        $this->dbConnect = $dbConnect;
        $this->setCacheDbName($dbConnect ? $dbConnect->getDbName() : null);
    }

    /**
     * @param string|null $verifyingFilePath
     *
     * @throws Exceptions\DataException
     * @throws Exceptions\ValidationException
     */
    public function setVerifyingFilePath(?string $verifyingFilePath) : void
    {
        $this->getHtmlCache()->setCheckFile($verifyingFilePath);
    }

    /**
     * @param string|null $prefix
     */
    public function setPrefix(?string $prefix) : void
    {
        $this->prefix = $prefix;
    }

    /**
     * @return array|null
     */
    public function getTags() : ?array
    {
        return $this->tags;
    }

    /**
     * @param array|null $tags
     */
    public function setTags(?array $tags) : void
    {
        $this->tags = $tags;
    }

    /**
     * @param string $idTag
     */
    public function setIdTag(string $idTag) : void
    {
        $this->idTag = $idTag;
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
     * Вернуть объект кэша запросов
     *
     * @return QueryCache
     *
     * @throws Exceptions\ValidationException
     * @throws \ReflectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ContainerTypeNotSupportingException
     * @throws \Scaleplan\DependencyInjection\Exceptions\DependencyInjectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ParameterMustBeInterfaceNameOrClassNameException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ReturnTypeMustImplementsInterfaceException
     */
    protected function getQueryCache() : QueryCache
    {
        if (!$this->queryCache) {
            $this->queryCache = new QueryCache(
                $this->dbConnect,
                $this->request,
                $this->params,
                $this->tags,
                $this->requestSettings
            );
        }

        $this->queryCache->setCacheDbName($this->cacheDbName);

        return $this->queryCache;
    }

    /**
     * Вернуть объект кэша страниц
     *
     * @return HtmlCache
     *
     * @throws Exceptions\DataException
     * @throws Exceptions\ValidationException
     */
    protected function getHtmlCache() : HtmlCache
    {
        if (!$this->htmlCache) {
            $this->htmlCache = new HtmlCache(
                $this->request,
                $this->params,
                $this->tags ?? [],
                $this->requestSettings
            );
        }

        $this->htmlCache->setCacheDbName($this->cacheDbName);

        return $this->htmlCache;
    }

    /**
     * Получить данные БД
     *
     * @return DbResultInterface
     *
     * @throws Exceptions\DataException
     * @throws Exceptions\ValidationException
     * @throws \ReflectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ContainerTypeNotSupportingException
     * @throws \Scaleplan\DependencyInjection\Exceptions\DependencyInjectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ParameterMustBeInterfaceNameOrClassNameException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ReturnTypeMustImplementsInterfaceException
     * @throws \Scaleplan\Result\Exceptions\ResultException
     */
    public function getValue() : DbResultInterface
    {
        $getQuery = function () {
            return (new Query($this->request, $this->dbConnect, $this->params))->execute($this->prefix);
        };

        if (!$this->cacheEnable) {
            return $getQuery();
        }

        if ($this->getQueryCache()->isModifying()) {
            $result = $getQuery();
            $this->getQueryCache()->initTags($result);
            $this->getQueryCache()->setIdTag($this->idTag);

            return $result;
        }

        $result = $this->getQueryCache()->get();
        if ($result->getResult() === null) {
            $this->getQueryCache()->setLock();
            $result = $getQuery();
            $this->getQueryCache()->set($result);
        }

        return $result;
    }

    /**
     * Удаление элемента кэша запросов к БД
     *
     * @throws Exceptions\DataException
     * @throws Exceptions\MemcachedCacheException
     * @throws Exceptions\RedisCacheException
     * @throws Exceptions\RedisOperationException
     * @throws Exceptions\ValidationException
     * @throws \ReflectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ContainerTypeNotSupportingException
     * @throws \Scaleplan\DependencyInjection\Exceptions\DependencyInjectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ParameterMustBeInterfaceNameOrClassNameException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ReturnTypeMustImplementsInterfaceException
     */
    public function deleteValue() : void
    {
        $this->getQueryCache()->delete();
    }

    /**
     * Вернуть HTML
     *
     * @param $userId - идентификатор пользователя
     *
     * @return HTMLResult
     * @throws Exceptions\DataException
     * @throws Exceptions\ValidationException
     */
    public function getHtml(int $userId) : HTMLResult
    {
        $htmlCache = $this->getHtmlCache();
        $htmlCache->setUserId($userId);

        return $this->getHtmlCache()->get();
    }

    /**
     * @param HTMLResult $html
     * @param int $userId
     *
     * @throws Exceptions\DataException
     * @throws Exceptions\ValidationException
     */
    public function setHtml(HTMLResult $html, int $userId) : void
    {
        $htmlCache = $this->getHtmlCache();
        $htmlCache->setUserId($userId);
        $htmlCache->setTags($this->tags);
        $htmlCache->setIdTag($this->idTag);
        $htmlCache->setMinId($this->minId);
        $htmlCache->setMaxId($this->maxId);
        $htmlCache->set($html);
    }

    /**
     * Удаление элемента кэша страниц
     *
     * @throws Exceptions\DataException
     * @throws Exceptions\ValidationException
     */
    public function deleteHtml() : void
    {
        $this->getHtmlCache()->delete();
    }

    /**
     * Создать объект запроса и выполнить его
     *
     * @param string $request - текст запроса
     * @param array $params - параметры запроса
     * @param array $settings - настройки
     *
     * @return DbResultInterface|null
     *
     * @throws Exceptions\DataException
     * @throws Exceptions\ValidationException
     * @throws \ReflectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ContainerTypeNotSupportingException
     * @throws \Scaleplan\DependencyInjection\Exceptions\DependencyInjectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ParameterMustBeInterfaceNameOrClassNameException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ReturnTypeMustImplementsInterfaceException
     * @throws \Scaleplan\Result\Exceptions\ResultException
     */
    public static function execQuery(string $request, array $params = [], array $settings = []) : ?DbResultInterface
    {
        return static::getInstance($request, $params, $settings)->getValue();
    }

    /**
     * @param $userId
     *
     * @return string
     *
     * @throws Exceptions\DataException
     * @throws Exceptions\ValidationException
     * @throws \ReflectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ContainerTypeNotSupportingException
     * @throws \Scaleplan\DependencyInjection\Exceptions\DependencyInjectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ParameterMustBeInterfaceNameOrClassNameException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ReturnTypeMustImplementsInterfaceException
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
