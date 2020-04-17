<?php
declare(strict_types=1);

namespace Scaleplan\Data\Interfaces;

use Scaleplan\Result\Interfaces\HTMLResultInterface;

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
     * @return HTMLResultInterface
     */
    public function getHtml($userId) : HTMLResultInterface;

    /**
     * @param $html
     * @param int $userId
     *
     * @return void
     */
    public function setHtml($html, int $userId) : void;

    /**
     * @param string $idTag
     */
    public function setIdTag(string $idTag) : void;

    /**
     * @param string|null $verifyingFilePath
     */
    public function setVerifyingFilePath(?string $verifyingFilePath) : void;
}
