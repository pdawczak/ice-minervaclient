<?php

namespace Ice\MinervaClientBundle\Response;

use JMS\Serializer\Annotation as JMS;
use Doctrine\Common\Collections\ArrayCollection;

class FormError{
    /**
     * @var FormError[]
     * @JMS\Type("array<string, Ice\MinervaClientBundle\Response\FormError>")
     * @JMS\AccessType("public_method")
     */
    private $children = array();

    /**
     * @var string
     * @JMS\Exclude;
     */
    private $name;

    /**
     * @var FormError
     * @JMS\Exclude;
     */
    private $parent;

    /**
     * @var string[]
     * @JMS\Type("array<string>")
     */
    private $errors = array();

    /**
     * @param FormError[] $children
     * @return $this
     */
    public function setChildren($children)
    {
        foreach($children as $name=>$child){
            $child->setName($name)->setParent($this);
        }
        $this->children = $children;
        return $this;
    }

    /**
     * @return FormError[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param $errors
     * @return $this
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @return \string[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param bool $recursive
     * @return array
     */
    public function getErrorsAsAssociativeArray($recursive = false)
    {
        $errors = array();
        if($ownErrors = $this->getErrors()){
            $errors[$this->getName()] = $ownErrors;
        }
        if($recursive){
            foreach($this->getChildren() as $child){
                $errors = array_merge($errors, $child->getErrorsAsAssociativeArray($recursive));
            }
        }
        return $errors;
    }

    /**
     * @param string $name
     * @return FormError
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \Ice\JanusClientBundle\Response\FormError $parent
     * @return FormError
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return \Ice\JanusClientBundle\Response\FormError
     */
    public function getParent()
    {
        return $this->parent;
    }
}