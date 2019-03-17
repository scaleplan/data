<?php

namespace Scaleplan\Data;

use Scaleplan\Db\Db;
use Scaleplan\Db\Interfaces\DbInterface;
use Scaleplan\Db\pgDb;
use Scaleplan\Data\Exceptions\DbConnectException;
use Scaleplan\Data\Exceptions\ValidationException;
use Scaleplan\Result\DbResult;
use Scaleplan\SqlTemplater\SqlTemplater;

/**
 * Класс организации запросов к БД
 *
 * Class Query
 *
 * @package Scaleplan\Data
 */
class Query
{
    /**
     * Текст шаблона SQL-запроса
     *
     * @var string
     */
    protected $rawSql = '';

    /**
     * Текст запроса после обработки SqlTemplater::sql()
     *
     * @var string
     */
    protected $sql = '';

    /**
     * Параметры запроса до обработки SqlTemplater::sql()
     *
     * @var array
     */
    protected $rawParams = [];

    /**
     * Параметры запроса
     *
     * @var array
     */
    protected $params = [];

    /**
     * Подключение к РБД
     *
     * @var null|PgDb|Db
     */
    protected $dbConnect;

    /**
     * Результат запроса
     *
     * @var DbResult|null
     */
    protected $result;

    /**
     * Конструктор
     *
     * @param string $sql - необработанный текст запроса
     * @param DbInterface|null $dbConnect - подключение к РБД
     * @param array $params - необработанный массив параметров запроса
     *
     * @throws ValidationException
     */
    public function __construct(string $sql, DbInterface $dbConnect = null, array $params = [])
    {
        if (!$sql) {
            throw new ValidationException('Текст запроса пуст');
        }

        $this->rawSql = $sql;
        $this->rawParams = $params;
        $this->dbConnect = $dbConnect;
    }

    /**
     * Вернуть необработанный текст запроса
     *
     * @return string
     */
    public function getRawSql() : string
    {
        return $this->rawSql;
    }

    /**
     * Вернуть необработанный массив параметров запроса
     *
     * @return array
     */
    public function getRawParams() : array
    {
        return $this->rawParams;
    }

    /**
     * Вернуть текст запроса после обработки SqlTemplater::sql()
     *
     * @return string
     */
    public function getSql() : string
    {
        if (!$this->sql) {
            $sql = $this->rawSql;
            $params = $this->rawParams;
            [$this->sql, $this->params] = SqlTemplater::sql($sql, $params);
        }

        return $this->sql;
    }

    /**
     * Вернуть параметры запроса после обработки SqlTemplater::sql()
     *
     * @return array
     */
    public function getParams() : array
    {
        $this->getSql();

        return $this->params;
    }

    /**
     * Установить подключение к РБД
     *
     * @param Db $dbConnect - подключение к РБД
     */
    public function setDbConnect(Db $dbConnect) : void
    {
        $this->dbConnect = $dbConnect;
    }

    /**
     * Выполнить запрос
     *
     * @param string $prefix - префикс ключей
     *
     * @return DbResult
     *
     * @throws DbConnectException
     * @throws \Scaleplan\Db\Exceptions\InvalidIsolationLevelException
     * @throws \Scaleplan\Db\Exceptions\QueryCountNotMatchParamsException
     * @throws \Scaleplan\Db\Exceptions\QueryExecutionException
     * @throws \Scaleplan\Result\Exceptions\ResultException
     */
    public function execute(string $prefix = '') : DbResult
    {
        if (!$this->dbConnect) {
            throw new DbConnectException();
        }

        $result = $this->dbConnect->query($this->getSql(), $this->getParams());

        return $this->result = new DbResult($result, $prefix);
    }

    /**
     * Выполнить запрос асинхронно
     *
     * @return bool
     *
     * @throws DbConnectException
     * @throws \Scaleplan\Db\Exceptions\DbException
     */
    public function executeAsync() : bool
    {
        if (!$this->dbConnect || !($this->dbConnect instanceof PgDb)) {
            throw new DbConnectException();
        }

        return $this->dbConnect->async($this->getSql(), $this->getParams());
    }

    /**
     * Вернуть результат запроса
     *
     * @return DbResult
     */
    public function getResult() : DbResult
    {
        return $this->result;
    }

}
