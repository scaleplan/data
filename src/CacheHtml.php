<?php

namespace avtomon;

class CacheHtmlException extends CustomException
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
     * @param array $settings - настройки объекта
     *
     * @throws AbstractCacheItemException
     * @throws CacheHtmlException
     */
    public function __construct(string $url, array $params = [], $cacheConnect = null, array $tags = [], array $settings = [])
    {
        if (!$url || !preg_match(self::URL_TEMPLATE, $url)) {
            throw new CacheHtmlException('URL не передан или передан в неверном формате');
        }

        parent::__construct($url, $params, $cacheConnect, $settings);
    }

    /**
     * Установить пусть к файлу проверки
     *
     * @param string $filePath - путь к файлу
     */
    public function setCheckFile(string $filePath): void
    {
        $this->checkFile = $filePath;
    }

    /**
     * Проверить актуальность кэша по времени изменения файла
     *
     * @param int $cacheTime - время установки кэша
     *
     * @return bool
     *
     * @throws CacheHtmlException
     */
    protected function checkFileTime(int $cacheTime): bool
    {
        if (!$this->checkFile) {
            return true;
        }

        $fileTime = @filemtime($this->checkFile);
        if ($cacheTime < $fileTime) {
            return false;
        }

        return true;
    }

    /**
     * Получить данные элемента кэша
     *
     * @return HTMLResultItem|null
     *
     * @throws AbstractCacheItemException
     */
    public function get(): HTMLResultItem
    {
        $result = parent::get();
        $time = $result['time'] ?? 0;
        if (!$this->checkFileTime($time)) {
            $result = null;
        }

        return new HTMLResultItem($result['data'] ?? null);
    }

    /**
     * Установка тегов
     *
     * @param array $tags - массив тегов
     */
    public function setTags(array $tags = []): void
    {
        parent::setTags($tags);
    }
}