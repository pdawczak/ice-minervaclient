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
        $orderedStepProgresses = array();

        /**
         * Use a small offset here to ensure that duplicate step order values do not overwrite each other, and that
         * the given order is preserved in these cases.
         */
        $i = 0.0;
        foreach($stepProgresses as $stepProgress){
            $index = $stepProgress->getOrder() ? floatval($stepProgress->getOrder()) : 0.0;
            $orderedStepProgresses[strval($index+$i)] = $stepProgress;
            $stepProgress->setRegistrationProgress($this);
            $i+=0.01;
        }
        ksort($orderedStepProgresses, SORT_NUMERIC);

        $this->stepProgresses = array_values($orderedStepProgresses);
        return $this;
    }

    /**
     * @param StepProgress $stepProgress
     * @return $this
     */
    public function addStepProgress($stepProgress)
    {
        $stepProgress->setRegistrationProgress($this);
        $this->stepProgresses[] = $stepProgress;
        return $this;
    }

    /**
     * Return an ordered StepProgress list
     *
     * @return StepProgress[]
     */
    public function getStepProgresses()
    {
        return $this->stepProgresses;
    }
}