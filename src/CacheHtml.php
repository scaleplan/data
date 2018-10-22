<?php

namespace Scaleplan\Data;

use Scaleplan\Data\Exceptions\ValidationException;
use Scaleplan\Result\HTMLResult;

/**
 * Класс управления кэшированием HTML-страниц
 *
 * Class CacheHtml
 *
 * @package Scaleplan\Data
 */
class CacheHtml extends AbstractCacheItem
{
    /**
     * Регулярное выражение для проверки правильности формата передаваемого урла
     */
    protected const URL_TEMPLATE = '/^.+?\/[^\/]+$/';

    /**
     * Путь до файла, по которому будет проверяться актуальность кэша
     *
     * @var string
     */
    protected $checkFile = '';

    /**
     * @var string|int|null
     */
    protected $userId;

    /**
     * Конструктор
     *
     * @param string $url - текст урла
     * @param array $params - параметры запроса
     * @param null $cacheConnect - подключение к кэшу
     * @param array $settings - настройки объекта
     *
     * @throws Exceptions\DataException
     * @throws ValidationException
     * @throws \ReflectionException
     */
    public function __construct(string $url, array $params = [], $cacheConnect = null, array $settings = [])
    {
        if (!$url || !preg_match(self::URL_TEMPLATE, $url)) {
            throw new ValidationException('URL не передан или передан в неверном формате');
        }

        parent::__construct($url, $params, $cacheConnect, $settings);
    }

    /**
     * @param $userId
     */
    public function setUserId($userId) : void
    {
        $this->userId = $userId;
        $this->key = parent::getKey() . ":$userId";
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
     */
    public function get(): HTMLResult
    {
        $result = parent::get();
        $time = $result['time'] ?? 0;
        if (!$this->checkFileTime($time)) {
            $result = null;
        }

        return new HTMLResult($result['data'] ?? null);
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