<?php

namespace Ice\MinervaClientBundle\Entity;

use JMS\Serializer\Annotation as JMS;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class AcademicInformation
 * @package Ice\MinervaClientBundle\Entity
 * @JMS\AccessType("public_method")
 */
class AcademicInformation{
    /**
     * @var Booking[]|ArrayCollection;
     * @JMS\Type("array<Ice\MinervaClientBundle\Entity\Booking>")
     */
    private $bookings;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("registrationStatusCode")
     */
    private $registrationStatusCode;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("username")
     */
    private $iceId;

    /**
     * @var int
     * @JMS\Type("integer")
     * @JMS\SerializedName("courseId")
     */
    private $courseId;

    /**
     * @return ArrayCollection|Booking[]
     */
    public function getBookings()
    {
        return $this->bookings;
    }

    /**
     * @param Booking[] $bookings
     * @return $this
     */
    public function setBookings($bookings)
    {
        $this->bookings = $bookings;
        foreach($this->bookings as $booking){
            $booking->setAcademicInformation($this);
        }
        return $this;
    }

    /**
     * @param string $registrationStatusCode
     * @return AcademicInformation
     */
    public function setRegistrationStatusCode($registrationStatusCode)
    {
        $this->registrationStatusCode = $registrationStatusCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegistrationStatusCode()
    {
        return $this->registrationStatusCode;
    }

    /**
     * @param int $courseId
     * @return AcademicInformation
     */
    public function setCourseId($courseId)
    {
        $this->courseId = $courseId;
        return $this;
    }

    /**
     * @return int
     */
    public function getCourseId()
    {
        return $this->courseId;
    }

    /**
     * @param string $iceId
     * @return AcademicInformation
     */
    public function setIceId($iceId)
    {
        $this->iceId = $iceId;
        return $this;
    }

    /**
     * @return string
     */
    public function getIceId()
    {
        return $this->iceId;
    }

    /**
     * @return Booking|null
     */
    public function getActiveBooking(){
        if($this->bookings) return $this->bookings[0];
        return null;
    }
}