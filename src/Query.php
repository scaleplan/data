<?php

namespace Scaleplan\Data;

use Scaleplan\Data\Exceptions\DbConnectException;
use Scaleplan\Data\Exceptions\ValidationException;
use Scaleplan\Db\Db;
use Scaleplan\Db\Interfaces\DbInterface;
use Scaleplan\Db\PgDb;
use Scaleplan\Result\Interfaces\DbResultInterface;
use Scaleplan\Result\TranslatedDbResult;
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
    protected $sql;

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
    protected $params;

    /**
     * Подключение к РБД
     *
     * @var null|PgDb|Db
     */
    protected $dbConnect;

    /**
     * Результат запроса
     *
     * @var DbResultInterface|null
     */
    protected $result;

    /**
     * @var array|bool
     */
    protected $castings = true;

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
            throw new ValidationException('Текст запроса пуст.');
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
            [$this->sql, $this->params] = SqlTemplater::sql($sql, $params, $this->castings);
        }

        return $this->sql;
    }

    /**
     * @param array|bool $castings
     */
    public function setCastings($castings) : void
    {
        $this->castings = $castings;
    }

    /**
     * Вернуть параметры запроса после обработки SqlTemplater::sql()
     *
     * @return array
     */
    public function getParams() : array
    {
        if ($this->params === null) {
            $this->getSql();
        }

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
     * @param string|null $prefix - префикс ключей
     *
     * @return DbResultInterface
     *
     * @throws DbConnectException
     * @throws \Scaleplan\Db\Exceptions\InvalidIsolationLevelException
     * @throws \Scaleplan\Db\Exceptions\PDOConnectionException
     * @throws \Scaleplan\Db\Exceptions\QueryCountNotMatchParamsException
     * @throws \Scaleplan\Db\Exceptions\QueryExecutionException
     * @throws \Scaleplan\Result\Exceptions\ResultException
     */
    public function execute(string $prefix = null) : DbResultInterface
    {
        if (!$this->dbConnect) {
            throw new DbConnectException();
        }

        $result = $this->dbConnect->query($this->getSql(), $this->getParams());

        return $this->result = new TranslatedDbResult($result, $prefix);
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
     * @return DbResultInterface
     */
    public function getResult() : DbResultInterface
    {
        return $this->result;
    }
}
