<?php

namespace avtomon;

class CacheQueryException extends CustomException
{
}

class CacheQuery extends AbstractCacheItem
{
    /**
     * Подключение к РБД
     *
     * @var _PDO
     */
    protected $dbConnect = null;

    /**
     * Изменяет ли запрос данные БД
     *
     * @var bool
     */
    protected $isModifying = true;

    /**
     * Конструктор
     *
     * @param _PDO $dbConnect - подключение к РБД
     * @param string $request - текст SQL-запроса
     * @param array $params - параметры запроса
     * @param null $cacheConnect - подключение к кэшу
     * @param array $settings - настройки объекта
     *
     * @throws AbstractCacheItemException
     */
    public function __construct(_PDO $dbConnect, string $request, array $params = [], $cacheConnect = null, array $settings = [])
    {
        $this->dbConnect = $dbConnect;
        $this->request = $request;

        $this->generateTags();

        parent::__construct($request, $params, $cacheConnect, $settings);
    }

    /**
     * Инициализация тегов
     */
    protected function generateTags(): void
    {
        if ($this->tags || !$this->request) {
            return;
        }

        $editTags = $this->dbConnect->getEditTables($this->request);

        $this->isModifying = (bool) $editTags;

        $this->tags = $editTags ?: $this->dbConnect->getTables($this->request);
    }

    /**
     * Вернуть список таблиц, изменяемых запросом
     *
     * @return bool
     */
    public function getIsModifying(): bool
    {
        return $this->isModifying;
    }

    /**
     * Получить данные элемента кэша
     *
     * @return DbResultItem|null
     *
     * @throws AbstractCacheItemException
     */
    public function get(): DbResultItem
    {
        $result = parent::get();
        return new DbResultItem($result['data'] ?? null);
    }

    /**
     * Вернуть теги запроса
     *
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}