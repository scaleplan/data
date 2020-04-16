<?php
declare(strict_types=1);

namespace Scaleplan\Data;

use Scaleplan\Db\Db;
use Scaleplan\Db\Interfaces\DbInterface;
use Scaleplan\Db\Interfaces\TableTagsInterface;
use Scaleplan\Result\DbResult;
use Scaleplan\Result\TranslatedDbResult;
use function Scaleplan\DependencyInjection\get_required_container;

/**
 * Класс кэширования результатов запросов к БД
 *
 * Class QueryCache
 *
 * @package Scaleplan\Data
 */
class QueryCache extends AbstractCacheItem
{
    /**
     * Подключение к РБД
     *
     * @var Db|null
     */
    protected $dbConnect;

    /**
     * Изменяет ли запрос данные БД
     *
     * @var bool
     */
    protected $isModifying = false;

    /**
     * QueryCache constructor.
     *
     * @param DbInterface $dbConnect - подключение к РБД
     * @param string $request - текст SQL-запроса
     * @param array $params - параметры запроса
     * @param array|null $tags - теги запроса
     * @param array $settings - настройки объекта
     *
     * @throws Exceptions\ValidationException
     * @throws \ReflectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ContainerTypeNotSupportingException
     * @throws \Scaleplan\DependencyInjection\Exceptions\DependencyInjectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ParameterMustBeInterfaceNameOrClassNameException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ReturnTypeMustImplementsInterfaceException
     */
    public function __construct(
        DbInterface $dbConnect,
        string $request,
        array $params = [],
        array $tags = null,
        array $settings = []
    )
    {
        $this->request = $request;
        $this->tags = $tags;
        $this->setDbConnect($dbConnect);

        parent::__construct($request, $params, $settings);
    }

    /**
     * @return Db|null
     */
    public function getDbConnect() : ?Db
    {
        return $this->dbConnect;
    }

    /**
     * @param DbInterface|null $dbConnect
     *
     * @throws \ReflectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ContainerTypeNotSupportingException
     * @throws \Scaleplan\DependencyInjection\Exceptions\DependencyInjectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ParameterMustBeInterfaceNameOrClassNameException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ReturnTypeMustImplementsInterfaceException
     */
    public function setDbConnect(?DbInterface $dbConnect) : void
    {
        $this->dbConnect = $dbConnect;
        /** @var TableTagsInterface $tableTags */
        $tableTags = get_required_container(TableTagsInterface::class, [$dbConnect]);
        $editTags = $tableTags->getEditTables($this->request);
        $this->isModifying = (bool)$editTags;
        $this->tags = $this->tags ?? ($editTags ?: $tableTags->getTables($this->request));
    }

    /**
     * Изменяющий ли запрос
     *
     * @return bool
     */
    public function isModifying() : bool
    {
        return $this->isModifying;
    }

    /**
     * Установить тип запроса: изменяющий (true) или читающий (false)
     *
     * @param bool $flag
     */
    public function setIsModifying(bool $flag = true) : void
    {
        $this->isModifying = $flag;
    }

    /**
     * Получить данные элемента кэша
     *
     * @return DbResult
     *
     * @throws Exceptions\DataException
     * @throws \Scaleplan\Cache\Exceptions\MemcachedCacheException
     * @throws \Scaleplan\Cache\Exceptions\RedisCacheException
     * @throws \Scaleplan\Result\Exceptions\ResultException
     */
    public function get() : DbResult
    {
        return new TranslatedDbResult(parent::get()->getData());
    }

    /**
     * Вернуть теги запроса
     *
     * @return array
     */
    public function getTags() : array
    {
        return $this->tags;
    }
}
