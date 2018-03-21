<?php

namespace avtomon;

class QueryException extends \Exception
{
}

class Query
{
    /**
     * Текст шаблона SQL-запроса
     *
     * @var string
     */
    private $rawSql = '';

    /**
     * Текст запроса после обработки SqlTemplater::sql()
     *
     * @var string
     */
    private $sql = '';

    /**
     * Параметры запроса до обработки SqlTemplater::sql()
     *
     * @var array
     */
    private $rawParams = [];

    /**
     * Параметры запроса
     *
     * @var array
     */
    private $params = [];

    /**
     * Подключение к РБД
     *
     * @var null|_PDO
     */
    private $dbConnect = null;

    /**
     * Результат запроса
     *
     * @var dbResultItem
     */
    private $result = null;

    /**
     * Конструктор
     *
     * @param string $sql - необработанный текст запроса
     * @param _PDO|null $dbConnect - подключение к РБД
     * @param array $params - необработанный массив параметров запроса
     */
    public function __construct(_PDO $dbConnect = null, string $sql, array $params = [])
    {
        if (!$sql) {
            throw new QueryException('Текст запроса пуст');
        }

        $this->rawSql = $query;
        $this->rawParams = $params;
        $this->dbConnect = $dbConnect;
    }

    /**
     * Вернуть необработанный текст запроса
     *
     * @return string
     */
    public function getRawSql(): string
    {
        return $this->rawSql;
    }

    /**
     * Вернуть необработанный массив параметров запроса
     *
     * @return array
     */
    public function getRawParams(): array
    {
        return $this->rawParams;
    }

    /**
     * Вернуть текст запроса после обработки SqlTemplater::sql()
     *
     * @return string
     */
    public function getSql(): string
    {
        if (!$this->sql) {
            list($this->sql, $this->params) = SqlTemplater::sql($this->rawSql, $this->rawParams);
        }

        return $this->sql;
    }

    /**
     * Вернуть параметры запроса после обработки SqlTemplater::sql()
     *
     * @return array
     */
    public function getParams(): array
    {
        if (!$this->sql) {
            list($this->sql, $this->params) = SqlTemplater::sql($this->rawSql, $this->rawParams);
        }

        return $this->params;
    }

    /**
     * Установить подключение к РБД
     *
     * @param _PDO $dbConnect - подключение к РБД
     */
    public function setDbConnect(_PDO $dbConnect)
    {
        $this->dbConnect = $dbConnect;
    }

    /**
     * Выполнить запрос
     *
     * @param string $prefix - префикс ключеей
     *
     * @return dbResultItem
     */
    public function execute(string $prefix = ''): dbResultItem
    {
        if (!$this->dbConnect) {
            throw new QueryException('Сначала установите подключение к базе данных');
        }

        $result = $this->dbConnect->query($this->getSql(), $this->getParams());

        return $this->result = new dbResultItem($result, $prefix);
    }

    /**
     * Выполнить запрос асинхронно
     *
     * @return bool
     *
     * @throws QueryException
     */
    public function executeAsync(): bool
    {
        if (!$this->dbConnect) {
            throw new QueryException('Сначала установите подключение к базе данных');
        }

        return $this->dbConnect->async($this->getSql(), $this->getParams());
    }

    public function getResult(): dbResultItem
    {
        return $this->result;
    }

}