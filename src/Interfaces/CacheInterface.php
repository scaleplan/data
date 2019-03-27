<?php

namespace Scaleplan\Data\Interfaces;

use Scaleplan\Result\HTMLResult;

/**
 * Interface CacheInterface
 *
 * @package Scaleplan\Main\Interfaces
 */
interface CacheInterface
{
    /**
     * @param array $tags
     */
    public function setTags(array $tags) : void;

    /**
     * @param $cacheConnect
     */
    public function setCacheConnect($cacheConnect) : void;

    /**
     * @param int $userId
     *
     * @return HTMLResult
     */
    public function getHtml(int $userId) : HTMLResult;

    /**
     * @param HTMLResult $html
     * @param array $tags
     *
     * @return mixed
     */
    public function setHtml(HTMLResult $html, array $tags = []);
}
