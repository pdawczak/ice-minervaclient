<?php
namespace Ice\MinervaClientBundle\Entity;

use JMS\Serializer\Annotation as JMS;

class FieldValue
{
    /**
     * @var string
     * @JMS\Type("string")
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
     * @JMS\Exclude
     */
    private $value;

    /**
     * @param string $fieldName
     * @return FieldName
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
}