<?php

namespace Ice\MinervaClientBundle\Entity;

use JMS\Serializer\Annotation as JMS;
use Doctrine\Common\Collections\ArrayCollection;
use Ice\VeritasClientBundle\Entity\Course;
use Ice\VeritasClientBundle\Service\VeritasClient;

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
     * @JMS\SerializedName("paymentStatusCode")
     */
    private $paymentStatusCode;

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
     * @var Course
     * @JMS\Exclude()
     */
    private $course;

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

    /**
     * Return true if the course registration is complete
     *
     * @return bool
     */
    public function isRegistrationComplete()
    {
        return $this->getRegistrationStatusCode() === MinervaStatus::RegistrationComplete;
    }

    /**
     * @param string $paymentStatusCode
     * @return AcademicInformation
     */
    public function setPaymentStatusCode($paymentStatusCode)
    {
        $this->paymentStatusCode = $paymentStatusCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentStatusCode()
    {
        return $this->paymentStatusCode;
    }

    /**
     * @param \Ice\VeritasClientBundle\Entity\Course $course
     * @return AcademicInformation
     */
    public function setCourse($course)
    {
        $this->course = $course;
        return $this;
    }

    /**
     * Returns the VeritasClientBundle course object against this entity. If it is not set and a $veritasClient is
     * provided, it will be fetched from the API.
     *
     * @param VeritasClient $veritasClient
     * @return Course
     * @throws \RuntimeException if not set
     */
    public function getCourse($veritasClient = null)
    {
        if (null === $this->course) {
            if (null === $veritasClient) {
                throw new \RuntimeException("Course not set");
            }
            else {
                $this->course = $veritasClient->getCourse($this->getCourseId());
            }
        }
        return $this->course;
    }
}