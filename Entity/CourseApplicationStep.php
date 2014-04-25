<?php
namespace Ice\MinervaClientBundle\Entity;

use JMS\Serializer\Annotation as JMS;

class CourseApplicationStep
{
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("stepName")
     */
    private $stepName;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("stepVersion")
     */
    private $stepVersion;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("description")
     */
    private $description;

    /**
     * @var int
     * @JMS\Type("integer")
     */
    private $order;

    /**
     * @var null|\DateTime
     * @JMS\Type("DateTime")
     */
    private $began;

    /**
     * @var null|\DateTime
     * @JMS\Type("DateTime")
     */
    private $updated;

    /**
     * @var null|\DateTime
     * @JMS\Type("DateTime")
     */
    private $completed;

    /**
     * @var CourseApplicationFieldValue[]
     * @JMS\Type("array<Ice\MinervaClientBundle\Entity\CourseApplicationFieldValue>")
     * @JMS\AccessType("public_method")
     * @JMS\SerializedName("fieldValues")
     */
    private $fieldValues = array();

    /**
     * @param \DateTime|null $began
     * @return CourseApplicationStep
     */
    public function setBegan($began)
    {
        $this->began = $began;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getBegan()
    {
        return $this->began;
    }

    /**
     * @param \DateTime|null $completed
     * @return CourseApplicationStep
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * @param \Ice\MinervaClientBundle\Entity\CourseApplicationFieldValue[] $fieldValues
     * @return CourseApplicationStep
     */
    public function setFieldValues($fieldValues)
    {
        $this->fieldValues = $fieldValues;
        return $this;
    }

    /**
     * @return \Ice\MinervaClientBundle\Entity\CourseApplicationFieldValue[]
     */
    public function getFieldValues()
    {
        return $this->fieldValues;
    }

    /**
     * @param int $order
     * @return CourseApplicationStep
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
     * @param string $stepName
     * @return CourseApplicationStep
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
     * @param string $stepVersion
     * @return CourseApplicationStep
     */
    public function setStepVersion($stepVersion)
    {
        $this->stepVersion = $stepVersion;
        return $this;
    }

    /**
     * @return string
     */
    public function getStepVersion()
    {
        return $this->stepVersion;
    }

    /**
     * @param \DateTime|null $updated
     * @return CourseApplicationStep
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param string $description
     * @return CourseApplicationStep
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
     * @return bool
     */
    public function isComplete()
    {
        return $this->completed !== null;
    }
}
