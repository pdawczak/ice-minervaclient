<?php
namespace Ice\MinervaClientBundle\Entity;

use JMS\Serializer\Annotation as JMS;

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
    private $fieldValues;

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
}