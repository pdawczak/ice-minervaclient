<?php
namespace Ice\MinervaClientBundle\Entity;

use JMS\Serializer\Annotation as JMS;

class BookingItem
{
    /**
     * @var Booking
     * @JMS\Exclude
     */
    private $booking;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $code;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("statusCode");
     */
    private $statusCode;

    /**
     * @var Category
     * @JMS\Type("Ice\MinervaClientBundle\Entity\Category")
     */
    private $category;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $description;

    /**
     * @var int
     * @JMS\Type("integer")
     */
    private $price;

    /**
     * @param \Ice\MinervaClientBundle\Entity\Booking $booking
     * @return BookingItem
     */
    public function setBooking($booking)
    {
        $this->booking = $booking;
        return $this;
    }

    /**
     * @return \Ice\MinervaClientBundle\Entity\Booking
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * @param string $description
     * @return BookingItem
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

    /**
     * @param int $price
     * @return BookingItem
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param \Ice\MinervaClientBundle\Entity\Category $category
     * @return BookingItem
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return \Ice\MinervaClientBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $code
     * @return BookingItem
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $statusCode
     * @return BookingItem
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets all appropriate properties on this booking item to match a course booking item. WILL NOT set the category.
     *
     * @param \Ice\VeritasClientBundle\Entity\BookingItem $courseBookingItem
     * @return BookingItem
     */
    public function setAllByCourseBookingItem(\Ice\VeritasClientBundle\Entity\BookingItem $courseBookingItem)
    {
        $this
            ->setCode($courseBookingItem->getCode())
            ->setDescription($courseBookingItem->getTitle())
            ->setPrice($courseBookingItem->getPrice())
        ;
        return $this;
    }
    
    /**
     * @return bool
     */
    public function isCourseAccommodation()
    {
        return $this->getCategory()->getId() === Category::COURSE_ACCOMMODATION_CATEGORY;
    }

    /**
     * @return bool
     */
    public function isAdditionalAccommodation()
    {
        return $this->getCategory()->getId() === Category::ADDITIONAL_ACCOMMODATION_CATEGORY;
    }

    /**
     * @return bool
     */
    public function isTuition()
    {
        return $this->getCategory()->getId() === Category::TUITION_CATEGORY;
    }

    /**
     * @return bool
     */
    public function isEveningPlatter()
    {
        return $this->getCategory()->getId() === Category::EVENING_PLATTER_CATEGORY;
    }
}