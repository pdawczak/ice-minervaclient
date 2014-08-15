<?php
namespace Ice\MinervaClientBundle\Entity;

use JMS\Serializer\Annotation as JMS;

class FieldValue
{
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("fieldName")
     */
    private $fieldName;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\AccessType("public_method")
     * @JMS\SerializedName("value");
     */
    private $valueSerialized;

    /**
     * @var int
     * @JMS\Type("integer")
     */
    private $order;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $description;

    /**
     * @JMS\Exclude
     */
    private $value;

    /**
     * @param string $fieldName
     * @return FieldValue
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        $this->valueSerialized = serialize($value);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param $valueSerialized
     * @return $this
     */
    public function setValueSerialized($valueSerialized)
    {
        $this->valueSerialized = $valueSerialized;
        $this->value = unserialize($valueSerialized);
        return $this;
    }

    /**
     * @return string
     */
    public function getValueSerialized()
    {
        return $this->valueSerialized;
    }

    /**
     * @param string $description
     * @return FieldValue
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
     * @param int $order
     * @return FieldValue
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }
}