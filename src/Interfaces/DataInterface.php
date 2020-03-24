<?php

namespace Scaleplan\Data\Interfaces;

use Scaleplan\Db\Interfaces\DbInterface;
use Scaleplan\Result\HTMLResult;
use Scaleplan\Result\Interfaces\DbResultInterface;

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
     * Установить подключение к РБД
     *
     * @param DbInterface $dbConnect
     */
    public function setDbConnect(DbInterface $dbConnect) : void;

    /**
     * @param string|null $verifyingFilePath
     */
    public function setVerifyingFilePath(?string $verifyingFilePath) : void;

    /**
     * @param string|null $prefix
     */
    public function setPrefix(?string $prefix) : void;

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
     * @return DbResultInterface
     */
    public function getValue() : DbResultInterface;

    /**
     * Удаление элемента кэша запросов к БД
     */
    public function deleteValue() : void ;

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
     * @param $userId - идентификатор пользователя
     */
    public function setHtml(HTMLResult $html, int $userId) : void;

    /**
     * Удаление элемента кэша страниц
     */
    public function deleteHtml() : void ;

    /**
     * Создать объект запроса и выполнить его
     *
     * @param string $request - текст запроса
     * @param array $params - параметры запроса
     * @param array $settings - настройки
     *
     * @return DbResultInterface|null
     */
    public static function execQuery(string $request, array $params = [], array $settings = []) : ?DbResultInterface;

    /**
     * @param $userId
     *
     * @return string
     */
    public function getCache($userId) : string;
}
