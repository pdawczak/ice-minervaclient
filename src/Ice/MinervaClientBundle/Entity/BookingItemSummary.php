<?php

namespace Ice\MinervaClientBundle\Entity;

use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("ALL")
 */
class BookingItemSummary
{
    /**
     * @var string
     *
     * @JMS\Expose
     * @JMS\Type("string")
     */
    private $code;

    /**
     * @var int
     *
     * @JMS\Expose
     * @JMS\Type("integer")
     */
    private $bookings = 0;

    /**
     * @var int
     *
     * @JMS\Expose
     * @JMS\Type("integer")
     */
    private $allocated = 0;

    /**
     * @var int
     *
     * @JMS\Expose
     * @JMS\Type("integer")
     */
    private $cancelled = 0;

    /**
     * Number of bookings with an active allocation for this item.
     *
     * An allocated BookingItem cannot be allocated or purchased by anyone else.
     *
     * @return int
     */
    public function getAllocated()
    {
        return $this->allocated;
    }

    /**
     * Number of bookings with this BookingItem that have been cancelled.
     *
     * @return int
     */
    public function getCancelled()
    {
        return $this->cancelled;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Number of bookings associated with this BookingItem.
     *
     * Not all of them may be allocated to an attendee. Some could be cancelled or no longer reserved.
     *
     * @return int
     */
    public function getBookings()
    {
        return $this->bookings;
    }
}
