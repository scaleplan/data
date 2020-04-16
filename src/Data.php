<?php
declare(strict_types=1);

namespace Scaleplan\Data;

use Scaleplan\Cache\Exceptions\MemcachedCacheException;
use Scaleplan\Cache\Exceptions\MemcachedOperationException;
use Scaleplan\Cache\Exceptions\RedisCacheException;
use Scaleplan\Cache\Exceptions\RedisOperationException;
use Scaleplan\Data\Interfaces\CacheInterface;
use Scaleplan\Data\Interfaces\DataInterface;
use Scaleplan\Db\Interfaces\DbInterface;
use Scaleplan\InitTrait\InitTrait;
use Scaleplan\Result\DbResult;
use Scaleplan\Result\HTMLResult;
use Scaleplan\Result\Interfaces\DbResultInterface;
use function Scaleplan\Helpers\get_env;

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
     * @var null|Query
     */
    protected $query;

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
     * @var bool
     */
    protected $isAsync = false;

    /**
     * @var string
     */
    protected $idField;

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
     * Конструктор
     *
     * @param string $request - текст запроса
     * @param array $params - параметры запроса
     * @param array $settings - настройки
     */
    protected function __construct(string $request, array $params = [], array $settings = [])
    {
        if (null !== get_env('CACHE_ENABLE')) {
            $this->setCacheEnable((bool)getenv('CACHE_ENABLE'));
        }

        $this->requestSettings = $this->initObject($settings);

        $this->request = $request;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getIdField() : string
    {
        return $this->idField;
    }

    /**
     * @param string $idField
     */
    public function setIdField(string $idField) : void
    {
        $this->idField = $idField;
    }

    /**
     * @return bool
     */
    public function isAsync() : bool
    {
        return $this->isAsync;
    }

    /**
     * @param bool $isAsync
     */
    public function setIsAsync(bool $isAsync = true) : void
    {
        $this->isAsync = $isAsync;
    }

    /**
     * @param bool $cacheEnable
     */
    public function setCacheEnable(bool $cacheEnable) : void
    {
        $this->cacheEnable = $cacheEnable;
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
     * @param bool|array $castings
     *
     * @throws Exceptions\ValidationException
     */
    public function setCastings($castings) : void
    {
        $this->getQuery()->setCastings($castings);
    }

    /**
     * @return Query
     *
     * @throws Exceptions\ValidationException
     */
    public function getQuery() : Query
    {
        if (!$this->query) {
            $this->query = new Query($this->request, $this->dbConnect, $this->params);
        }

        return $this->query;
    }

    /**
     * Установить параметры запроса
     *
     * @param array $params - параметры
     */
    public function setParams(array $params) : void
    {
        $this->params = $params;
        $this->htmlCache = null;
        $this->queryCache = null;
        $this->query = null;
    }

    /**
     * Установить подключение к РБД
     *
     * @param DbInterface|null $dbConnect
     *
     * @throws \ReflectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ContainerTypeNotSupportingException
     * @throws \Scaleplan\DependencyInjection\Exceptions\DependencyInjectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ParameterMustBeInterfaceNameOrClassNameException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ReturnTypeMustImplementsInterfaceException
     */
    public function setDbConnect(DbInterface $dbConnect) : void
    {
        $this->dbConnect = $dbConnect;
        $this->queryCache && $this->queryCache->setDbConnect($dbConnect);
        $this->query && $this->query->setDbConnect($dbConnect);
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
        $this->htmlCache = null;
        $this->queryCache = null;
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
     * Выполнить запрос
     *
     * @return DbResultInterface
     *
     * @throws Exceptions\DataException
     * @throws Exceptions\DbConnectException
     * @throws Exceptions\ValidationException
     * @throws MemcachedCacheException
     * @throws MemcachedOperationException
     * @throws RedisCacheException
     * @throws \ReflectionException
     * @throws \Scaleplan\Db\Exceptions\DbException
     * @throws \Scaleplan\Db\Exceptions\InvalidIsolationLevelException
     * @throws \Scaleplan\Db\Exceptions\PDOConnectionException
     * @throws \Scaleplan\Db\Exceptions\QueryCountNotMatchParamsException
     * @throws \Scaleplan\Db\Exceptions\QueryExecutionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ContainerTypeNotSupportingException
     * @throws \Scaleplan\DependencyInjection\Exceptions\DependencyInjectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ParameterMustBeInterfaceNameOrClassNameException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ReturnTypeMustImplementsInterfaceException
     * @throws \Scaleplan\Result\Exceptions\ResultException
     */
    public function getValue() : DbResultInterface
    {
        if (!$this->cacheEnable) {
            return $this->getQuery()->execute($this->prefix);
        }

        if ($this->isAsync) {
            $this->getQuery()->executeAsync();
            return new DbResult(null);
        }

        if ($this->getQueryCache()->isModifying()) {
            $result = $this->getQuery()->execute($this->prefix);
            $this->getQueryCache()->setTags($this->tags);
            $this->getQueryCache()->setIdField($this->idField);
            $this->getQueryCache()->setIdTag($this->idTag);
            $this->getQueryCache()->initTags($result);

            return $result;
        }

        $result = $this->getQueryCache()->get();
        if ($result->getResult() === null) {
            $this->getQueryCache()->setLock();
            $result = $this->getQuery()->execute($this->prefix);
            $this->getQueryCache()->setTags($this->tags);
            $this->getQueryCache()->setIdField($this->idField);
            $this->getQueryCache()->setIdTag($this->idTag);
            $this->getQueryCache()->set($result);
        }

        return $result;
    }

    /**
     * Удаление элемента кэша запросов к БД
     *
     * @throws Exceptions\DataException
     * @throws MemcachedCacheException
     * @throws RedisCacheException
     * @throws RedisOperationException
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
     *
     * @throws Exceptions\DataException
     * @throws Exceptions\ValidationException
     * @throws MemcachedCacheException
     * @throws RedisCacheException
     */
    public function getHtml($userId) : HTMLResult
    {
        $this->getHtmlCache()->setUserId($userId);

        return $this->getHtmlCache()->get();
    }

    /**
     * @param HTMLResult $html
     * @param $userId
     *
     * @throws Exceptions\DataException
     * @throws Exceptions\ValidationException
     * @throws MemcachedCacheException
     * @throws MemcachedOperationException
     * @throws RedisCacheException
     */
    public function setHtml(HTMLResult $html, $userId) : void
    {
        $this->getHtmlCache()->setUserId($userId);
        $this->getHtmlCache()->setTags($this->tags);
        $this->getHtmlCache()->setIdField($this->idField);
        $this->getHtmlCache()->setIdTag($this->idTag);
        $this->getHtmlCache()->setMinId($this->minId);
        $this->getHtmlCache()->setMaxId($this->maxId);
        $this->getHtmlCache()->set($html);
    }

    /**
     * Удаление элемента кэша страниц
     *
     * @throws Exceptions\DataException
     * @throws Exceptions\ValidationException
     * @throws MemcachedCacheException
     * @throws RedisCacheException
     * @throws RedisOperationException
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
     * @throws Exceptions\DbConnectException
     * @throws Exceptions\ValidationException
     * @throws MemcachedCacheException
     * @throws MemcachedOperationException
     * @throws RedisCacheException
     * @throws \ReflectionException
     * @throws \Scaleplan\Db\Exceptions\DbException
     * @throws \Scaleplan\Db\Exceptions\InvalidIsolationLevelException
     * @throws \Scaleplan\Db\Exceptions\PDOConnectionException
     * @throws \Scaleplan\Db\Exceptions\QueryCountNotMatchParamsException
     * @throws \Scaleplan\Db\Exceptions\QueryExecutionException
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
     * @throws Exceptions\DbConnectException
     * @throws Exceptions\ValidationException
     * @throws MemcachedCacheException
     * @throws MemcachedOperationException
     * @throws RedisCacheException
     * @throws \ReflectionException
     * @throws \Scaleplan\Db\Exceptions\DbException
     * @throws \Scaleplan\Db\Exceptions\InvalidIsolationLevelException
     * @throws \Scaleplan\Db\Exceptions\PDOConnectionException
     * @throws \Scaleplan\Db\Exceptions\QueryCountNotMatchParamsException
     * @throws \Scaleplan\Db\Exceptions\QueryExecutionException
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
