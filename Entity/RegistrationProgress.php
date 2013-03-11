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
     * @return Booking
     */
    public function getBooking()
    {
        return $this->booking;
    }
}