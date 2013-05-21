<?php

namespace Ice\MinervaClientBundle\Entity;

use JMS\Serializer\Annotation as JMS;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class AcademicInformation
 * @package Ice\MinervaClientBundle\Entity
 * @JMS\AccessType("public_method")
 */
class AcademicInformation
{
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
     * @JMS\SerializedName("applicationStatusCode")
     */
    private $applicationStatusCode;

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
        foreach ($this->bookings as $booking) {
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
    public function getActiveBooking()
    {
        if ($this->bookings) return $this->bookings[0];
        return null;
    }

    /**
     * @return string
     */
    public function getApplicationStatusCode()
    {
        return $this->applicationStatusCode;
    }

    /**
     * @param string $applicationStatusCode
     * @return AcademicInformation
     */
    public function setApplicationStatusCode($applicationStatusCode)
    {
        $this->applicationStatusCode = $applicationStatusCode;
        return $this;
    }

    /**
     * Return true if the course application has accepted status, false otherwise
     *
     * @return bool
     */
    public function isApplicationAccepted()
    {
        return $this->getApplicationStatusCode() === MinervaStatus::ApplicationAccepted;
    }

    /**
     * Return true if the course application has rejected status, false otherwise
     *
     * @return bool
     */
    public function isApplicationRejected()
    {
        return $this->getApplicationStatusCode() === MinervaStatus::ApplicationRejected;
    }

    /**
     * Return true if an application has been received but not accepted or rejected
     *
     * @return bool
     */
    public function isApplicationPending()
    {
        return $this->getApplicationStatusCode() !== null &&
            !$this->isApplicationAccepted() &&
            !$this->isApplicationRejected();
    }
}