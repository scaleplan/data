<?php

namespace Scaleplan\Data;

use Scaleplan\Data\Exceptions\CacheDriverNotSupportedException;
use Scaleplan\Data\Exceptions\CacheException;
use Scaleplan\Data\Interfaces\CacheInterface;
use Scaleplan\Data\Interfaces\DataInterface;
use Scaleplan\Db\Interfaces\DbInterface;
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
     * @var null|DbInterface
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
     * Свойства запроса
     *
     * @var array
     */
    protected $requestSettings = [];

    /**
     * Путь к файлу, по которому будет проверяться акутуальность кэша
     *
     * @var string|null
     */
    protected $verifyingFilePath = '';

    /**
     * Префикс имен результирующих полей
     *
     * @var string
     */
    protected $prefix = '';

    /**
     * @var array|null
     */
    protected $tags;

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
     *
     * @throws CacheDriverNotSupportedException
     */
    public function setCacheConnect($cacheConnect) : void
    {
        if ($cacheConnect !== null && !($cacheConnect instanceof \Redis) && !($cacheConnect instanceof \Memcached)) {
            throw new CacheDriverNotSupportedException('Cache driver ' . gettype($cacheConnect) . ' not supporting.');
        }

        $this->cacheConnect = $cacheConnect;
    }

    /**
     * Установить подключение к РБД
     *
     * @param DbInterface|null $dbConnect
     */
    public function setDbConnect(?DbInterface $dbConnect) : void
    {
        $this->dbConnect = $dbConnect;
    }

    /**
     * @param string|null $verifyingFilePath
     */
    public function setVerifyingFilePath(?string $verifyingFilePath) : void
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
                $this->cacheConnect,
                $this->requestSettings
            );
        }

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
                $this->cacheConnect,
                $this->requestSettings
            );
        }

        return $this->htmlCache;
    }

    /**
     * Получить данные БД
     *
     * @return DbResult
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
    public function getValue() : DbResult
    {
        $getQuery = function () {
            return (new Query($this->request, $this->dbConnect, $this->params))->execute($this->prefix);
        };

        if ($this->getQueryCache()->isModifying()) {
            $result = $getQuery();
            $this->getQueryCache()->initTags();
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
     * @return bool
     *
     * @throws Exceptions\ValidationException
     * @throws \ReflectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ContainerTypeNotSupportingException
     * @throws \Scaleplan\DependencyInjection\Exceptions\DependencyInjectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ParameterMustBeInterfaceNameOrClassNameException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ReturnTypeMustImplementsInterfaceException
     */
    public function deleteValue() : bool
    {
        return $this->getQueryCache()->delete();
    }

    /**
     * Вернуть HTML
     *
     * @param $userId
     *
     * @return HTMLResult
     * @throws Exceptions\DataException
     * @throws Exceptions\ValidationException
     */
    public function getHtml(int $userId) : HTMLResult
    {
        $htmlCache = $this->getHtmlCache();
        $htmlCache->setCheckFile($this->verifyingFilePath);
        $htmlCache->setUserId($userId);
        return $this->getHtmlCache()->get();
    }

    /**
     * Сохранить к кэше HTML-страницу
     *
     * @param HTMLResult $html - HTML
     * @param array|null $tags - теги
     *
     * @throws Exceptions\DataException
     * @throws Exceptions\ValidationException
     */
    public function setHtml(HTMLResult $html, array $tags = []) : void
    {
        $htmlCache = $this->getHtmlCache();
        $htmlCache->setTags($tags);
        if (!$htmlCache->set($html)) {
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
     */
    public function deleteHtml() : bool
    {
        return $this->getHtmlCache()->delete();
    }

    /**
     * Создать объект запроса и выполнить его
     *
     * @param string $request - текст запроса
     * @param array $params - параметры запроса
     * @param array $settings - настройки
     *
     * @return DbResult|null
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
    public static function execQuery(string $request, array $params = [], array $settings = []) : ?DbResult
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
