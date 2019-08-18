<?php

namespace Scaleplan\Data;

use Scaleplan\Data\Exceptions\ValidationException;
use Scaleplan\Result\HTMLResult;

/**
 * Класс управления кэшированием HTML-страниц
 *
 * Class HtmlCache
 *
 * @package Scaleplan\Data
 */
class HtmlCache extends AbstractCacheItem
{
    /**
     * Регулярное выражение для проверки правильности формата передаваемого урла
     */
    protected const URL_TEMPLATE = '/^.+?\/[^\/]+$/';

    /**
     * Путь до файла, по которому будет проверяться актуальность кэша
     *
     * @var string|null
     */
    protected $checkFile = '';

    /**
     * @var int|null
     */
    protected $userId;

    /**
     * HtmlCache constructor.
     *
     * @param string $url
     * @param array $params
     * @param array $tags
     * @param array $settings
     *
     * @throws ValidationException
     */
    public function __construct(
        string $url,
        array $params = [],
        array $tags = [],
        array $settings = []
    ) {
        if (!$url || !preg_match(static::URL_TEMPLATE, $url)) {
            throw new ValidationException('URL не передан или передан в неверном формате');
        }

        parent::__construct($url, $params, $settings);
        $this->tags = $tags;
    }

    /**
     * @param $userId
     */
    public function setUserId($userId) : void
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getKey() : string
    {
        return parent::getKey() . ":{$this->userId}";
    }

    /**
     * Установить пусть к файлу проверки
     *
     * @param string|null $filePath - путь к файлу
     */
    public function setCheckFile(?string $filePath): void
    {
        $this->checkFile = $filePath;
    }

    /**
     * Проверить актуальность кэша по времени изменения файла
     *
     * @param int $cacheTime - время установки кэша
     *
     * @return bool
     */
    protected function checkFileTime(int $cacheTime): bool
    {
        if (!$this->checkFile) {
            return true;
        }

        return $cacheTime >= @filemtime($this->checkFile);
    }

    /**
     * Получить данные элемента кэша
     *
     * @return HTMLResult
     *
     * @throws Exceptions\DataException
     * @throws \Scaleplan\Cache\Exceptions\MemcachedCacheException
     * @throws \Scaleplan\Cache\Exceptions\RedisCacheException
     */
    public function get(): HTMLResult
    {
        $result = parent::get();
        if (!$this->checkFileTime($result->getTime())) {
            $result->setData(null);
        }

        return new HTMLResult($result->getData());
    }
}
