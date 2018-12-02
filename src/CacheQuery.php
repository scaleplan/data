<?php

namespace Scaleplan\Data;

use Scaleplan\Db\Db;
use Scaleplan\Result\DbResult;

/**
 * Класс кэширования результатов запросов к БД
 *
 * Class CacheQuery
 *
 * @package Scaleplan\Data
 */
class CacheQuery extends AbstractCacheItem
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
     * @param Db $dbConnect - подключение к РБД
     * @param string $request - текст SQL-запроса
     * @param array $params - параметры запроса
     * @param null $cacheConnect - подключение к кэшу
     * @param array $settings - настройки объекта
     *
     * @throws Exceptions\DataException
     * @throws \ReflectionException
     */
    public function __construct(Db $dbConnect, string $request, array $params = [], $cacheConnect = null, array $settings = [])
    {
        $this->dbConnect = $dbConnect;
        $this->request = $request;

        $this->generateTags();

        parent::__construct($request, $params, $cacheConnect, $settings);
    }

    /**
     * Инициализация тегов
     */
    protected function generateTags() : void
    {
        if ($this->tags || !$this->request) {
            return;
        }

        $editTags = $this->dbConnect->getEditTables($this->request);

        $this->isModifying = (bool)$editTags;

        $this->tags = $editTags ?: $this->dbConnect->getTables($this->request);
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