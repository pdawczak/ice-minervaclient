<?php

namespace Ice\MinervaClientBundle\Entity;

use JMS\Serializer\Annotation as JMS;
use Doctrine\Common\Collections\ArrayCollection;

class AcademicInformation{
    /**
     * @var Booking[]|ArrayCollection;
     */
    private $bookings;

    /**
     * @return ArrayCollection|Booking[]
     */
    public function getBookings()
    {
        return $this->bookings;
    }
}