<?php

namespace Scaleplan\Data;

use Scaleplan\Db\Db;
use Scaleplan\Db\Interfaces\DbInterface;
use Scaleplan\Result\DbResult;

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
     * Конструктор
     *
     * @param DbInterface $dbConnect - подключение к РБД
     * @param string $request - текст SQL-запроса
     * @param array $params - параметры запроса
     * @param array|null $tags - теги запроса
     * @param null $cacheConnect - подключение к кэшу
     * @param array $settings - настройки объекта
     *
     * @throws Exceptions\ValidationException
     */
    public function __construct(
        DbInterface $dbConnect,
        string $request,
        array $params = [],
        array $tags = null,
        $cacheConnect = null,
        array $settings = []
    ) {
        $this->dbConnect = $dbConnect;
        $this->request = $request;

        $editTags = $this->dbConnect->getEditTables($this->request);
        $this->isModifying = (bool)$editTags;
        $this->tags = $tags ?? ($editTags ?: $this->dbConnect->getTables($this->request));

        parent::__construct($request, $params, $cacheConnect, $settings);
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
    public function setIsModifying(\bool $flag = true) : void
    {
        $this->isModifying = $flag;
    }

    /**
     * Получить данные элемента кэша
     *
     * @return DbResult
     *
     * @throws Exceptions\DataException
     * @throws \Scaleplan\Result\Exceptions\ResultException
     */
    public function get() : DbResult
    {
        $result = parent::get();
        return new DbResult($result['data'] ?? null);
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
