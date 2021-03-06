<?php
namespace Ice\MinervaClientBundle\Entity;

use Ice\MinervaClientBundle\Exception\NotFoundException;
use Ice\VeritasClientBundle\Entity\BookingItem as CourseBookingItem;
use JMS\Serializer\Annotation as JMS;

class Booking
{
    /**
     * @var int
     * @JMS\Type("integer")
     */
    private $id;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("reference")
     */
    private $bookingReference;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("bookedBy")
     */
    private $bookedBy;

    /**
     * @var \DateTime
     * @JMS\Type("DateTime")
     * @JMS\SerializedName("bookingDate")
     */
    private $bookingDate;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("orderReference")
     */
    private $orderReference;

    /**
     * @var \DateTime
     * @JMS\Type("DateTime")
     * @JMS\SerializedName("cancelledDate")
     */
    private $cancelledDate;

    /**
     * @var AcademicInformation
     * @JMS\SerializedName("academicInformation");
     * @JMS\Type("Ice\MinervaClientBundle\Entity\AcademicInformation")
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
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("paymentGroupReference")
     */
    private $paymentGroupReference;

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
        foreach ($this->bookingItems as $bookingItem) {
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

    /**
     * @param CourseBookingItem $courseBookingItem
     * @return Booking
     */
    public function addBookingItemByCourseBookingItem(CourseBookingItem $courseBookingItem)
    {
        $newItem = new BookingItem();
        $newItem->setAllByCourseBookingItem($courseBookingItem);
        $newItem->setBooking($this);
        $this->bookingItems[] = $newItem;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $bookedBy
     * @return Booking
     */
    public function setBookedBy($bookedBy)
    {
        $this->bookedBy = $bookedBy;
        return $this;
    }

    /**
     * @return string
     */
    public function getBookedBy()
    {
        return $this->bookedBy;
    }

    /**
     * @return int
     */
    public function getBookingTotalPriceInPence()
    {
        $total = 0;
        foreach ($this->getBookingItems() as $item) {
            $total += $item->getPrice();
        }
        return $total;
    }

    /**
     * @return \DateTime
     */
    public function getCancelledDate()
    {
        return $this->cancelledDate;
    }

    /**
     * True if the items on this booking are allocated to the delegate (permanently or temporarily)
     *
     * @throws \LogicException if the booking does not have an AcademicInformation set
     * @return bool
     */
    public function isAllocated()
    {
        if (!$this->getAcademicInformation()) {
            throw new \LogicException("Is allocated cannot be called on a booking which is not attached to AcademicInformation");
        }
        //TODO: In future should return true if the booking is allocated temporarily.
        return $this->getAcademicInformation()->getRegistrationStatusCode() === MinervaStatus::RegistrationComplete &&
            !in_array($this->getAcademicInformation()->getPaymentStatusCode(), array(null, MinervaStatus::PaymentArranged));
    }

    /**
     * @param string $orderReference
     * @return Booking
     */
    public function setOrderReference($orderReference)
    {
        $this->orderReference = $orderReference;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderReference()
    {
        return $this->orderReference;
    }

    /**
     * @return \DateTime
     */
    public function getBookingDate()
    {
        return $this->bookingDate;
    }

    /**
     * @return string
     */
    public function getPaymentGroupReference()
    {
        return $this->paymentGroupReference;
    }

    /**
     * @param string $paymentGroupReference
     * @return $this
     */
    public function setPaymentGroupReference($paymentGroupReference)
    {
        $this->paymentGroupReference = $paymentGroupReference;
        return $this;
    }

    /**
     * @return string
     */
    public function getBookingReference()
    {
        return $this->bookingReference;
    }

    /**
     * @param string $bookingReference
     */
    public function setBookingReference($bookingReference)
    {
        $this->bookingReference = $bookingReference;
    }
}
