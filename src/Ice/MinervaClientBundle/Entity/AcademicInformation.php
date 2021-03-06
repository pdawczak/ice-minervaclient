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
     * @var CourseApplication[]|ArrayCollection;
     * @JMS\Type("array<Ice\MinervaClientBundle\Entity\CourseApplication>")
     * @JMS\SerializedName("courseApplications")
     */
    private $courseApplications;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("enrolmentStatusCode")
     */
    private $enrolmentStatusCode;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("bursaryStatusCode")
     */
    private $bursaryStatusCode;

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
     * @param \Doctrine\Common\Collections\ArrayCollection|\Ice\MinervaClientBundle\Entity\CourseApplication[] $courseApplications
     * @return AcademicInformation
     */
    public function setCourseApplications($courseApplications)
    {
        $this->courseApplications = $courseApplications;
        foreach ($this->courseApplications as $courseApplication) {
            $courseApplication->setAcademicInformation($this);
        }
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|\Ice\MinervaClientBundle\Entity\CourseApplication[]
     */
    public function getCourseApplications()
    {
        return $this->courseApplications;
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
        foreach ($this->getBookings() as $booking) {
            if (!$booking->getCancelledDate()) {
                return $booking;
            }
        }

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
     * Return true if the course application has in progress status, false otherwise
     *
     * @return bool
     */
    public function isApplicationInProgress()
    {
        return $this->getApplicationStatusCode() === MinervaStatus::ApplicationInProgress;
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
     * Return true if the course application has lapsed status, false otherwise
     *
     * @return bool
     */
    public function isApplicationLapsed()
    {
        return $this->getApplicationStatusCode() === MinervaStatus::ApplicationLapsed;
    }

    /**
     * Return true if the course application has been withdrawn by the student
     *
     * @return bool
     */
    public function isApplicationWithdrawn()
    {
        return $this->getApplicationStatusCode() === MinervaStatus::ApplicationWithdrawnByStudent;
    }

    /**
     * Return true if an application has been received but not accepted, rejected, lapsed or withdrawn
     *
     * @return bool
     */
    public function isApplicationPending()
    {
        return $this->getApplicationStatusCode() !== null &&
            !$this->isApplicationAccepted() &&
            !$this->isApplicationRejected() &&
            !$this->isApplicationLapsed() &&
            !$this->isApplicationInProgress() &&
            !$this->isApplicationWithdrawn();
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
     * Return true if the payment status is (exactly) arranged
     *
     * @return bool
     */
    public function isPaymentStatusArranged()
    {
        return $this->getPaymentStatusCode() === MinervaStatus::PaymentArranged;
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
            } else {
                $this->course = $veritasClient->getCourse($this->getCourseId());
            }
        }
        return $this->course;
    }

    /**
     * @param string $bursaryStatusCode
     * @return AcademicInformation
     */
    public function setBursaryStatusCode($bursaryStatusCode)
    {
        $this->bursaryStatusCode = $bursaryStatusCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getBursaryStatusCode()
    {
        return $this->bursaryStatusCode;
    }

    /**
     * @param string $enrolmentStatusCode
     * @return AcademicInformation
     */
    public function setEnrolmentStatusCode($enrolmentStatusCode)
    {
        $this->enrolmentStatusCode = $enrolmentStatusCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getEnrolmentStatusCode()
    {
        return $this->enrolmentStatusCode;
    }

    /**
     * @return bool
     */
    public function isEnrolmentReadyToEnrol()
    {
        return $this->getEnrolmentStatusCode() === MinervaStatus::EnrolmentReadyToEnrol;
    }

    /**
     * @return bool
     */
    public function isEnrolmentEnrolled()
    {
        return $this->getEnrolmentStatusCode() === MinervaStatus::EnrolmentEnrolled;
    }

    /**
     * @return bool
     */
    public function isEnrolmentFailed()
    {
        return $this->getEnrolmentStatusCode() === MinervaStatus::EnrolmentFailed;
    }

    /**
     * @return bool
     */
    public function hasEnrolmentStatus()
    {
        return $this->getEnrolmentStatusCode() !== null;
    }

    /**
     * @return bool
     */
    public function isBursaryAwarded()
    {
        return $this->getBursaryStatusCode() === MinervaStatus::BursaryAwarded;
    }

    /**
     * @return bool
     */
    public function isBursaryDeclined()
    {
        return $this->getBursaryStatusCode() === MinervaStatus::BursaryDeclined;
    }

    /**
     * @return bool
     */
    public function isBursaryRejected()
    {
        return $this->getBursaryStatusCode() === MinervaStatus::BursaryRejected;
    }

    /**
     * @return bool
     */
    public function isBursaryLapsed()
    {
        return $this->getBursaryStatusCode() === MinervaStatus::BursaryLapsed;
    }

    /**
     * @return bool
     */
    public function isBursaryPending()
    {
        return $this->getBursaryStatusCode() === MinervaStatus::BursaryChecked ||
            $this->getBursaryStatusCode() === MinervaStatus::BursaryReceived;
    }

    /**
     * @return bool
     */
    public function hasBursaryStatus()
    {
        return $this->getBursaryStatusCode() !== null;
    }

    /**
     * @return bool
     */
    public function hasPaymentStatus()
    {
        return $this->getPaymentStatusCode() !== null;
    }

    /**
     * @return bool
     */
    public function hasApplicationStatus()
    {
        return $this->getApplicationStatusCode() !== null;
    }
}