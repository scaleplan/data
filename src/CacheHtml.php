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
     * Путь до файла, по которому будет проверяться актуальность кэша
     *
     * @var string
     */
    protected $checkFile = '';

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
     * Проверить актуальность кэша по времени изменения файла
     *
     * @param int $cacheTime - время установки кэша
     *
     * @return bool
     *
     * @throws MShellException
     */
    protected function checkFileTime(int $cacheTime): bool
    {
        if (!$this->checkFile) {
            return true;
        }

        $fileTime = filemtime($this->checkFile);
        if ($fileTime === false) {
            throw new MShellException('Передан неправильный путь к файлу');
        }

        if ($cacheTime < $fileTime) {
            return false;
        }

        return true;
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
        $time = $result['time'] ?? 0;
        if (!$this->checkFileTime($time)) {
            $result = null;
        }

        return $result ? new HTMLResultItem($result['data']) : null;
    }
}