<?php
namespace Ice\MinervaClientBundle\Entity;

use JMS\Serializer\Annotation as JMS;
use Ice\MinervaClientBundle\Exception\NotFoundException;

class StepProgress
{
    /**
     * @var RegistrationProgress
     * @JMS\Exclude
     */
    private $registrationProgress;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("stepName")
     */
    private $stepName;

    /**
     * @var int
     * @JMS\Type("integer")
     * @JMS\SerializedName("stepVersion")
     */
    private $stepVersion;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $description;

    /**
     * @var int
     * @JMS\Type("integer")
     */
    private $order;

    /**
     * @var \DateTime
     * @JMS\Type("DateTime")
     */
    private $began;

    /**
     * @var \DateTime
     * @JMS\Type("DateTime")
     */
    private $updated;

    /**
     * @var \DateTime
     * @JMS\Type("DateTime")
     */
    private $completed;

    /**
     * @var FieldValue[]
     * @JMS\Type("array<Ice\MinervaClientBundle\Entity\FieldValue>")
     * @JMS\SerializedName("fieldValues")
     */
    private $fieldValues = array();

    /**
     * @var FieldValue[]
     * @JMS\Exclude
     */
    private $fieldValuesByName = array();

    /**
     * @param $registrationProgress
     * @return $this
     */
    public function setRegistrationProgress($registrationProgress)
    {
        $this->registrationProgress = $registrationProgress;
        return $this;
    }

    /**
     * @return RegistrationProgress
     */
    public function getRegistrationProgress()
    {
        return $this->registrationProgress;
    }

    /**
     * @param string $stepName
     * @return StepProgress
     */
    public function setStepName($stepName)
    {
        $this->stepName = $stepName;
        return $this;
    }

    /**
     * @return string
     */
    public function getStepName()
    {
        return $this->stepName;
    }

    /**
     * @param \DateTime $began
     * @return StepProgress
     */
    public function setBegan($began)
    {
        $this->began = $began;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBegan()
    {
        return $this->began;
    }

    /**
     * @param \DateTime $completed
     * @return StepProgress
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * @param \DateTime $updated
     * @return StepProgress
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param int $order
     * @return StepProgress
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

    /**
     * @param string $description
     * @return StepProgress
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

    public function setFieldValues($fieldValues)
    {
        $this->fieldValues = $fieldValues;
        return $this;
    }

    /**
     * @return array|FieldValue[]
     */
    public function getFieldValues()
    {
        return $this->fieldValues;
    }

    /**
     * @param $fieldName
     * @return FieldValue
     * @throws \Ice\MinervaClientBundle\Exception\NotFoundException
     */
    public function getFieldValueByName($fieldName)
    {
        foreach($this->fieldValues as $fieldValue){
            if($fieldValue->getFieldName() === $fieldName){
                return $fieldValue;
            }
        }
        throw new NotFoundException("Field value with name [".$fieldName."] not present");
    }

    /**
     * @param string $name
     * @param int $order
     * @param string $description
     * @param mixed $value The PHP object to be serialized
     * @return $this
     */
    public function setFieldValue($name, $order, $description, $value){
        try{
            $existing = $this->getFieldValueByName($name);
            $existing->setOrder($order)
                ->setDescription($description)
                ->setValue($value);
        }
        catch(NotFoundException $e){
            $fieldValue = new FieldValue();
            $fieldValue->setFieldName($name)
                ->setValue($value)
                ->setOrder($order)
                ->setDescription($description);
            $this->fieldValues[] = $fieldValue;
        }
        return $this;
    }

    /**
     * @param int $stepVersion
     * @return StepProgress
     */
    public function setStepVersion($stepVersion)
    {
        $this->stepVersion = $stepVersion;
        return $this;
    }

    /**
     * @return int
     */
    public function getStepVersion()
    {
        return $this->stepVersion;
    }
}