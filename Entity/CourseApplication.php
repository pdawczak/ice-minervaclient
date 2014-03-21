<?php
namespace Ice\MinervaClientBundle\Entity;

use JMS\Serializer\Annotation as JMS;

class CourseApplication
{
    /**
     * @var null|AcademicInformation
     */
    private $academicInformation;

    /**
     * @var int
     * @JMS\Type("integer")
     */
    private $id;

    /**
     * @var null|\DateTime
     * @JMS\Type("DateTime")
     */
    private $began;

    /**
     * @var null|\DateTime
     * @JMS\Type("DateTime")
     */
    private $updated;

    /**
     * @var null|\DateTime
     * @JMS\Type("DateTime")
     */
    private $completed;

    /**
     * @var CourseApplicationStep[]
     * @JMS\Type("array<Ice\MinervaClientBundle\Entity\CourseApplicationStep>")
     * @JMS\AccessType("public_method")
     * @JMS\SerializedName("courseApplicationSteps")
     */
    private $courseApplicationSteps;

    public function __construct()
    {
        $this->courseApplicationSteps = [];
    }

    /**
     * @param \Ice\MinervaClientBundle\Entity\AcademicInformation|null $academicInformation
     * @return CourseApplication
     */
    public function setAcademicInformation($academicInformation)
    {
        $this->academicInformation = $academicInformation;
        return $this;
    }

    /**
     * @return \Ice\MinervaClientBundle\Entity\AcademicInformation|null
     */
    public function getAcademicInformation()
    {
        return $this->academicInformation;
    }

    /**
     * @param \DateTime|null $began
     * @return CourseApplication
     */
    public function setBegan($began)
    {
        $this->began = $began;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getBegan()
    {
        return $this->began;
    }

    /**
     * @param \DateTime|null $completed
     * @return CourseApplication
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * @param int $id
     * @return CourseApplication
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @param \DateTime|null $updated
     * @return CourseApplication
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \Ice\MinervaClientBundle\Entity\CourseApplicationStep[] $courseApplicationSteps
     * @return CourseApplication
     */
    public function setCourseApplicationSteps($courseApplicationSteps)
    {
        $this->courseApplicationSteps = $courseApplicationSteps;
        return $this;
    }

    /**
     * @return \Ice\MinervaClientBundle\Entity\CourseApplicationStep[]
     */
    public function getCourseApplicationSteps()
    {
        return $this->courseApplicationSteps;
    }

    /**
     * @param $name
     * @return CourseApplicationStep|null
     */
    public function getCourseApplicationStepByName($name)
    {
        foreach ($this->getCourseApplicationSteps() as $step) {
            if($step->getStepName() === $name) {
                return $step;
            }
        }
        return null;
    }
}
