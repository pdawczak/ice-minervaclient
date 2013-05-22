<?php
namespace Ice\MinervaClientBundle\Entity;

use JMS\Serializer\Annotation as JMS;

class Category
{
    /**
     * @var integer
     * @JMS\Type("integer")
     */
    private $id;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $description;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $code;

    /**
     * @param string $description
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $code
     * @return Category
     * @deprecated Use setId() instead
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     * @deprecated Use getId() instead
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $id
     *
     * @return Category
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}