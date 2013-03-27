<?php
namespace Ice\MinervaClientBundle\Exception;

use Ice\MinervaClientBundle\Response\FormError;

class ValidationException extends ClientErrorResponseException{
    /** @var FormError */
    private $form;

    public function __construct(FormError $form, $message = '', $code = 0, \Exception $previous = null){
        $this->form = $form;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return \Ice\MinervaClientBundle\Response\FormError
     */
    public function getForm()
    {
        return $this->form;
    }
}