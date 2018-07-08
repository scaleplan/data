<?php

namespace avtomon;

/**
 * Класс ошибок
 *
 * Class QueryException
 * @package avtomon
 */
class QueryException extends CustomException
{
}

/**
 * Класс организации запросов к БД
 *
 * Class Query
 * @package avtomon
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
     * @var null|_PDO
     */
    protected $dbConnect;

    /**
     * Результат запроса
     *
     * @var DbResultItem|null
     */
    protected $result;

    /**
     * Конструктор
     *
     * @param string $sql - необработанный текст запроса
     * @param _PDO|null $dbConnect - подключение к РБД
     * @param array $params - необработанный массив параметров запроса
     *
     * @throws QueryException
     */
    public function __construct(_PDO $dbConnect = null, string $sql, array $params = [])
    {
        if (!$sql) {
            throw new QueryException('Текст запроса пуст');
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
    public function getParams(): array
    {
        $this->getSql();

        return $this->params;
    }

    /**
     * Установить подключение к РБД
     *
     * @param _PDO $dbConnect - подключение к РБД
     */
    public function setDbConnect(_PDO $dbConnect): void
    {
        $this->dbConnect = $dbConnect;
    }

    /**
     * Выполнить запрос
     *
     * @param string $prefix - префикс ключей
     *
     * @return DbResultItem
     *
     * @throws DbResultItemException
     * @throws QueryException
     */
    public function execute(string $prefix = ''): DbResultItem
    {
        if (!$this->dbConnect) {
            throw new QueryException('Сначала установите подключение к базе данных');
        }

        $result = $this->dbConnect->query($this->getSql(), $this->getParams());

        return $this->result = new DbResultItem($result, $prefix);
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

    /**
     * Вернуть результат запроса
     *
     * @return DbResultItem
     */
    public function getResult(): DbResultItem
    {
        return $this->result;
    }

}