<?php

namespace avtomon;

class CacheHtmlException extends AbstractCacheItemException
{
}

class CacheHtml extends AbstractCacheItem
{
    /**
     * Регулярное выражение для проверки правильности формата передаваемого урла
     */
    const URL_TEMPLATE = '/^.+?\/[^\/]+$/';

    /**
     * CacheHtml constructor.
     * @param string $url - текст урла
     * @param array $params - параметры запроса
     * @param null $cacheConnect - подключение к кэшу
     * @param array $tags - массив тегов
     * @param array|null $settings - настройки объекта
     *
     * @throws AbstractCacheItemException
     * @throws CacheHtmlException
     */
    public function __construct(string $url, array $params = [], $cacheConnect = null, array $tags = [], array $settings = null)
    {
        if (!$url || preg_match(self::URL_TEMPLATE, $query)) {
            throw new CacheHtmlException('URL не передан или передан в неверном формате');
        }

        parent::__construct($url, $params, $cacheConnect, $settings);

        $this->initTags($tags);
    }

    /**
     * Инициализация тегов
     *
     * @param array $tags - массив тегов
     */
    protected function initTags(?array $tags): void
    {
        if (!is_null($tags)) {
            $this->tags = $tags;
        }
    }

    /**
     * Получить данные элемента кэша
     *
     * @return HTMLResultItem|null
     *
     * @throws AbstractCacheItemException
     */
    public function get(): ?HTMLResultItem
    {
        $result = parent::get();
        return $result ? new HTMLResultItem($result['data']) : null;
    }
}