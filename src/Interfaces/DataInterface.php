<?php

namespace Scaleplan\Data\Interfaces;

use Scaleplan\Db\Interfaces\DbInterface;
use Scaleplan\Result\DbResult;
use Scaleplan\Result\HTMLResult;

/**
 * Основной класс получения данных
 *
 * Class DataStory
 *
 * @package Scaleplan\Data
 */
interface DataInterface
{
    /**
     * Установить тип запроса: изменяющий (true) или читающий (false)
     *
     * @param bool $flag
     */
    public function setIsModifying(bool $flag = true) : void;

    /**
     * Установить параметры запроса
     *
     * @param array $params - параметры
     */
    public function setParams(array $params) : void;

    /**
     * Установить посдключение к кэшу
     *
     * @param null|\Redis|\Memcached $cacheConnect - подключение к кэшу
     */
    public function setCacheConnect($cacheConnect) : void;

    /**
     * Установить подключение к РБД
     *
     * @param DbInterface|null $dbConnect
     */
    public function setDbConnect(?DbInterface $dbConnect) : void;

    /**
     * @param string|null $verifyingFilePath
     */
    public function setVerifyingFilePath(?string $verifyingFilePath) : void;

    /**
     * @param string $prefix
     */
    public function setPrefix(string $prefix) : void;

    /**
     * @return array|null
     */
    public function getTags() : ?array;

    /**
     * @param array|null $tags
     */
    public function setTags(?array $tags) : void;

    /**
     * Получить данные БД
     *
     * @return DbResult
     */
    public function getValue() : DbResult;

    /**
     * Удаление элемента кэша запросов к БД
     *
     * @return bool
     */
    public function deleteValue() : bool;

    /**
     * Вернуть HTML
     *
     * @param $userId
     *
     * @return HTMLResult
     */
    public function getHtml(int $userId) : HTMLResult;

    /**
     * Сохранить к кэше HTML-страницу
     *
     * @param HTMLResult $html - HTML
     * @param array|null $tags - теги
     */
    public function setHtml(HTMLResult $html, array $tags = []) : void;

    /**
     * Удаление элемента кэша страниц
     *
     * @return bool
     */
    public function deleteHtml() : bool;

    /**
     * Создать объект запроса и выполнить его
     *
     * @param string $request - текст запроса
     * @param array $params - параметры запроса
     * @param array $settings - настройки
     *
     * @return DbResult|null
     */
    public static function execQuery(string $request, array $params = [], array $settings = []) : ?DbResult;

    /**
     * @param $userId
     *
     * @return string
     */
    public function getCache($userId) : string;
}
