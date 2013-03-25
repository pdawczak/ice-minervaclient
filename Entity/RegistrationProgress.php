<?php
namespace Ice\MinervaClientBundle\Entity;

use JMS\Serializer\Annotation as JMS;

class RegistrationProgress{
    /**
     * @var Booking
     * @JMS\Exclude
     */
    private $booking;

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
     * @var StepProgress[]
     * @JMS\Type("array<Ice\MinervaClientBundle\Entity\StepProgress>")
     * @JMS\SerializedName("stepProgresses")
     * @JMS\AccessType("public_method")
     */
    private $stepProgresses;

    /**
     * @return Booking
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * @param \DateTime $began
     * @return RegistrationProgress
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
     * @return RegistrationProgress
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
     * @return RegistrationProgress
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
     * @param StepProgress[] $stepProgresses
     * @return $this
     */
    public function setStepProgresses($stepProgresses)
    {
        $this->stepProgresses = $stepProgresses;
        foreach($this->stepProgresses as $stepProgress){
            $stepProgress->setRegistrationProgress($this);
        }
        return $this;
    }

    /**
     * @return StepProgress[]
     */
    public function getStepProgresses()
    {
        return $this->stepProgresses;
    }
}