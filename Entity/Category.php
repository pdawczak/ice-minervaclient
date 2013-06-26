<?php
namespace Ice\MinervaClientBundle\Entity;

use JMS\Serializer\Annotation as JMS;

class Category
{
    const TUITION_CATEGORY = 5;
    const COURSE_ACCOMMODATION_CATEGORY = 6;
    const ADDITIONAL_ACCOMMODATION_CATEGORY = 7;
    const EVENING_PLATTER_CATEGORY = 8;
    const MISCELLANEOUS_CATEGORY = 9;
    const DISCOUNT_CATEGORY = 10;

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

    /**
     * @return bool
     */
    public function isTuition()
    {
        return $this->getId() === self::TUITION_CATEGORY;
    }

    /**
     * @return bool
     */
    public function isCourseAccommodation()
    {
        return $this->getId() === self::COURSE_ACCOMMODATION_CATEGORY;
    }

    /**
     * @return bool
     */
    public function isAdditionalAccommodation()
    {
        return $this->getId() === self::ADDITIONAL_ACCOMMODATION_CATEGORY;
    }

    /**
     * @return bool
     */
    public function isEveningPlatter()
    {
        return $this->getId() === self::EVENING_PLATTER_CATEGORY;
    }

    /**
     * @return bool
     */
    public function isMiscellaneous()
    {
        return $this->getId() === self::MISCELLANEOUS_CATEGORY;
    }

    /**
     * @return bool
     */
    public function isDiscount()
    {
        return $this->getId() === self::DISCOUNT_CATEGORY;
    }
}