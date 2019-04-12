<?php

namespace Scaleplan\Data;

use Scaleplan\Data\Exceptions\DataException;
use Scaleplan\InitTrait\InitTrait;

/**
 * Class CacheStructure
 *
 * @package Scaleplan\Data
 */
class CacheStructure
{
    use InitTrait;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var int
     */
    private $time = 0;

    /**
     * @var array
     */
    private $tags = [];

    /**
     * @var string
     */
    private $idTag = '';

    /**
     * @var int
     */
    private $maxId = 0;

    /**
     * @var int
     */
    private $minId = 0;

    /**
     * CacheStructure constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->initObject($data);
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data) : void
    {
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getTime() : int
    {
        return $this->time;
    }

    /**
     * @param int $time
     */
    public function setTime(int $time) : void
    {
        $this->time = $time;
    }

    /**
     * @return array
     */
    public function getTags() : array
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags) : void
    {
        $this->tags = $tags;
    }

    /**
     * @return string
     */
    public function getIdTag() : string
    {
        return $this->idTag;
    }

    /**
     * @param string $idTag
     */
    public function setIdTag(string $idTag) : void
    {
        $this->idTag = $idTag;
    }

    /**
     * @return int
     */
    public function getMaxId() : int
    {
        return $this->maxId;
    }

    /**
     * @param int $maxId
     */
    public function setMaxId(int $maxId) : void
    {
        $this->maxId = $maxId;
    }

    /**
     * @return int
     */
    public function getMinId() : int
    {
        return $this->minId;
    }

    /**
     * @param int $minId
     */
    public function setMinId(int $minId) : void
    {
        $this->minId = $minId;
    }

    /**
     * @return string
     *
     * @throws DataException
     */
    public function __toString()
    {
        $json = json_encode([
            'tags'   => $this->getTags(),
            'data'   => $this->getData(),
            'time'   => $this->getTime(),
            'max_id' => $this->getMaxId(),
            'min_id' => $this->getMinId(),
            'id_tag' => $this->getIdTag(),
        ], JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            throw new DataException('Не удалось сериализовать данные для кэширования');
        }

        return (string)$json;
    }
}
