<?php

namespace avtomon;

class CacheQueryException extends AbstractCacheItemException
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
     * Если запрос изменяющий, то в этом свойстве хранится список таблиц, которые изменяются этим запросом
     *
     * @var array
     */
    protected $editTags = [];

    /**
     * Конструктор
     *
     * @param _PDO $dbConnect - подключение к РБД
     * @param string $request - текст SQL-запроса
     * @param array $params - параметры запроса
     * @param null $cacheConnect - подключение к кэшу
     * @param array|null $settings - настройки объекта
     *
     * @throws AbstractCacheItemException
     */
    public function __construct(_PDO $dbConnect, string $request, array $params = [], $cacheConnect = null, array $settings = null)
    {
        $this->dbConnect = $dbConnect;

        parent::__construct($request, $params, $cacheConnect, $settings);

        $this->initTags();
    }

    /**
     * Инициализация тегов
     *
     * @throws AbstractCacheItemException
     */
    protected function initTags(): void
    {
        if (!$this->tags) {
            $this->editTags = $this->dbConnect->getEditTables($query);

            parent::initTags($this->editTags);

            $this->tags = $this->dbConnect->getTables($query);
        }
    }

    /**
     * Вернуть список таблиц, изменяемых запросом
     *
     * @return array
     */
    public function getEditTags(): array
    {
        return $this->editTags;
    }

    /**
     * Получить данные элемента кэша
     *
     * @return DbResultItem|null
     *
     * @throws AbstractCacheItemException
     */
    public function get(): ?DbResultItem
    {
        $result = parent::get();
        return $result ? new DbResultItem($result['data']) : null;
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