<?php
namespace Ice\MinervaClientBundle\Entity;

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
     * @JMS\SerializedName("bookedBy")
     */
    private $bookedBy;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("suborderGroup")
     */
    private $suborderGroup;

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
     */
    private $paymentGroupId;

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
     * @param string $paymentGroupId
     * @return Booking
     */
    public function setPaymentGroupId($paymentGroupId)
    {
        $this->paymentGroupId = $paymentGroupId;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentGroupId()
    {
        return $this->paymentGroupId;
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
     * @return string
     */
    public function getSuborderGroup()
    {
        return $this->suborderGroup;
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
     * Get the optional items which the user can't order. This is useful for feedback before asking them to revisit
     * their course registration.
     *
     * @return BookingItem[]|array
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function getNonRequiredBookingItemsOutOfStock ()
    {
        if (
            !$this->getAcademicInformation() ||
            !($course = $this->getAcademicInformation()->getCourse())
        ) {
            throw new \LogicException("getItemsOutOfStock requires an attached AcademicInformation with an attached Course");
        } else {
            $items = [];
            foreach ($this->getBookingItems() as $item) {
                if ($courseItem = $course->getBookingItemByCode($item->getCode())) {
                    if (!$courseItem->isInStock()) {
                        $items[] = $item;
                    }
                } else {
                    throw new \RuntimeException(
                        'No course booking item found to match '.$item->getCode(). ' on booking '.$this->getId()
                    );
                }
            }
            return $items;
        }
    }

    /**
     * @return \DateTime
     */
    public function getBookingDate()
    {
        return $this->bookingDate;
    }
}