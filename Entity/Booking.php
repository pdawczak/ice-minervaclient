<?php
namespace Ice\MinervaClientBundle\Entity;

use JMS\Serializer\Annotation as JMS;

class Booking{
    /**
     * @var AcademicInformation
     * @JMS\Exclude
     */
    private $academicInformation;

    /**
     * @var RegistrationProgress[]
     * @JMS\SerializedName("registrationProgress");
     * @JMS\Type("Ice\MinervaClientBundle\Entity\RegistrationProgress")
     */
    private $registrationProgress;

    /**
     * @var BookingItem[]
     * @JMS\Type("array<Ice\MinervaClientBundle\Entity\BookingItem>")
     * @JMS\SerializedName("bookingItems")
     * @JMS\AccessType("public_method")
     */
    private $bookingItems;

    /**
     * @return AcademicInformation
     */
    public function getAcademicInformation()
    {
        return $this->academicInformation;
    }

    /**
     * @return RegistrationProgress
     */
    public function getRegistrationProgress()
    {
        return $this->registrationProgress;
    }

    /**
     * @param \Ice\MinervaClientBundle\Entity\AcademicInformation $academicInformation
     * @return Booking
     */
    public function setAcademicInformation($academicInformation)
    {
        $this->academicInformation = $academicInformation;
        return $this;
    }

    /**
     * @param RegistrationProgress $registrationProgress
     * @return $this
     */
    public function setRegistrationProgress($registrationProgress)
    {
        $this->registrationProgress = $registrationProgress;
        return $this;
    }

    /**
     * @param $bookingItems
     * @return $this
     */
    public function setBookingItems($bookingItems)
    {
        $this->bookingItems = $bookingItems;
        foreach($this->bookingItems as $bookingItem){
            $bookingItem->setBooking($this);
        }
        return $this;
    }

    /**
     * @return BookingItem[]
     */
    public function getBookingItems()
    {
        return $this->bookingItems;
    }
}