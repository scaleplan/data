<?php

namespace Scaleplan\Data;

use Scaleplan\Data\Exceptions\DataException;
use Scaleplan\InitTrait\InitTrait;

/**
 * Class TagStructure
 *
 * @package Scaleplan\Data
 */
class TagStructure
{
    use InitTrait;

    /**
     * @var int
     */
    private $time = 0;

    /**
     * @var int
     */
    private $maxId = 0;

    /**
     * @var int
     */
    private $minId = 0;

    /**
     * @var string
     */
    private $name;

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name) : void
    {
        $this->name = $name;
    }

    /**
     * TagStructure constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->initObject($data);
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
     * @return array
     */
    public function toArray() : array
    {
        return [
            'max_id' => $this->getMaxId(),
            'min_id' => $this->getMinId(),
            'time'   => $this->getTime(),
        ];
    }

    /**
     * @return string
     *
     * @throws DataException
     */
    public function __toString()
    {
        $json = json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            throw new DataException('Не удалось сериализовать данные для кэширования');
        }

        return (string)$json;
    }
}
