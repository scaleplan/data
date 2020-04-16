<?php
declare(strict_types=1);

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
     * @param int $userId
     *
     * @return HTMLResult
     */
    public function getHtml($userId) : HTMLResult;

    /**
     * @param HTMLResult $html
     * @param int $userId
     *
     * @return mixed
     */
    public function setHtml(HTMLResult $html, int $userId);

    /**
     * @param string $idTag
     */
    public function setIdTag(string $idTag) : void;
}
