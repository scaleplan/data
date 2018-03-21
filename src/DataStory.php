<?php

namespace avtomon;

class DataStoryException extends \Exception
{
}

class DataStory
{
    /**
     * Трейт инициализации настроек
     */
    use InitTrait;

    /**
     * Настройки класса
     *
     * @var array
     */
    protected static $settings = [
        'dbConnect' => null,
        'cacheConnect' => null
    ];

    /**
     * Доступный инстансы класса
     *
     * @var array
     */
    protected static $instances = [];

    /**
     * Текст запроса
     *
     * @var string
     */
    protected $request = '';

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
    protected $dbConnect = null;

    /**
     * Подключение к хранилищу кэшей
     *
     * @var null|\Redis|\Memcached
     */
    protected $cacheConnect = null;

    /**
     * Объект кэша запросов
     *
     * @var null|CacheQuery
     */
    protected $cacheQuery = null;

    /**
     * Объект кэша страниц
     *
     * @var null|CacheHtml
     */
    protected $cacheHtml = null;

    /**
     * Создать или вернуть инстранс класса
     *
     * @param string $request - текст запроса
     * @param array $params - параметры запроса
     *
     * @return DataStory
     */
    public static function create(string $request, array $params = []): DataStory
    {
        $key = md5($request . serialize($params));
        if (!self::$instances[$key]) {
            self::$instances[$key] = new DataStory($request, $params);
        }

        return self::$instances[$key];
    }

    /**
     * Конструктор
     *
     * @param string $request - текст запроса
     * @param array $params - параметры запроса
     *
     * @throws DataStoryException
     */
    protected function __construct(string $request, array $params = [])
    {
        if (!$request) {
            throw new DataStoryException('Текст запроса пуст');
        }

        $this->initObject(self::$settings);

        $this->request = $request;
        $this->params = $params;
    }

    /**
     * Вернуть результат запроса к РБД
     *
     * @return DbResultItem|null
     *
     * @throws AbstractCacheItemException
     * @throws DataStoryException
     * @throws QueryException
     */
    public function getValue()
    {
        if (!$this->dbConnect) {
            throw new DataStoryException('Отсутствует подключение к РБД');
        }

        if (!$this->cacheConnect) {
            throw new DataStoryException('Отсутствует подключение к кэширующему хранилищу');
        }

        if (!$this->cacheQuery) {
            $this->cacheQuery = new CacheQuery($this->dbConnect, $this->request, $this->params, $this->cacheConnect);
        }

        $result = $this->cacheQuery->get();
        if (is_null($result)) {
            $result = (new Query($this->dbConnect, $this->request, $this->params))->execute();

            if (!$this->cacheQuery) {
                $this->cacheQuery->set($result);
            }
        }

        return $result;
    }

    /**
     * Вернуть HTML
     *
     * @return HTMLResultItem|null
     *
     * @throws AbstractCacheItemException
     * @throws DataStoryException
     */
    public function getHtml()
    {
        if (!$this->cacheConnect) {
            throw new DataStoryException('Отсутствует подключение к кэширующему хранилищу');
        }

        if (!$this->cacheQuery) {
            $this->cacheHtml = new CacheHtml($this->request, $this->params, $this->cacheConnect);
        }

        return $this->cacheHtml->get();
    }

    /**
     * Сохранить к кэше HTML-страницу
     *
     * @param HTMLResultItem $html - HTML
     * @param array|null $tags - теги
     *
     * @throws AbstractCacheItemException
     * @throws DataStoryException
     */
    public function setHtml(HTMLResultItem $html, array $tags = null): void
    {
        if (!$this->cacheConnect) {
            throw new DataStoryException('Отсутствует подключение к кэширующему хранилищу');
        }

        if (!$this->cacheQuery) {
            $this->cacheHtml = new CacheHtml($this->request, $this->params, $this->cacheConnect, $tags);
        }

        if (!$this->cacheQuery->set($html)) {
            throw new DataStoryException('Не удалось сохранить HTML в кэше');
        }
    }
}