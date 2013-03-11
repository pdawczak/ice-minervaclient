<?php
namespace Ice\MinervaClientBundle\Entity;

use JMS\Serializer\Annotation as JMS;

class Booking{
    /**
     * @var AcademicInformation
     * @JMS\Exclude
     */
    private $academicInformation;

    /**
     * @var RegistrationProgress[]
     */
    private $registrationProgresses;

    /**
     * @return AcademicInformation
     */
    public function getAcademicInformation()
    {
        return $this->academicInformation;
    }

    /**
     * @return RegistrationProgress[]
     */
    public function getRegistrationProgresses()
    {
        return $this->registrationProgresses;
    }
}