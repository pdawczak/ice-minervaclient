<?php

namespace Ice\MinervaClientBundle\Service;

use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Service\Client;

use Ice\MinervaClientBundle\Entity\AcademicInformation;
use Ice\MinervaClientBundle\Entity\MinervaStatus;
use Ice\MinervaClientBundle\Exception\ClientErrorResponseException;
use Ice\MinervaClientBundle\Exception\NotFoundException;
use Ice\MinervaClientBundle\Exception\ValidationException;
use Ice\MinervaClientBundle\Entity\Booking;
use JMS\Serializer\Serializer;


class MinervaClient
{
    /**
     * @var \Guzzle\Service\Client
     */
    private $client;

    /**
     * @var \JMS\Serializer\Serializer
     */
    private $serializer;

    /**
     * @param Client     $client
     * @param Serializer $serializer
     * @param string     $username
     * @param string     $password
     */
    public function __construct(Client $client, Serializer $serializer, $username, $password)
    {
        $this->client = $client;
        $this->client->setConfig(array(
            'curl.options' => array(
                'CURLOPT_USERPWD' => sprintf("%s:%s", $username, $password),
            ),
        ));
        $this->serializer = $serializer;
        $this->client->setDefaultHeaders(array(
            'Accept' => 'application/json',
        ));
    }

    public function setApplicationAccepted($username, $courseId)
    {
        return $this->setApplicationStatus($username, $courseId, MinervaStatus::ApplicationAccepted);
    }

    public function setApplicationApprovedByAcademic($username, $courseId)
    {
        return $this->setApplicationStatus($username, $courseId, MinervaStatus::ApplicationApprovedByAcademic);
    }

    public function setApplicationChecked($username, $courseId)
    {
        return $this->setApplicationStatus($username, $courseId, MinervaStatus::ApplicationChecked);
    }

    public function setApplicationLapsed($username, $courseId)
    {
        return $this->setApplicationStatus($username, $courseId, MinervaStatus::ApplicationLapsed);
    }

    public function setApplicationReceived($username, $courseId)
    {
        return $this->setApplicationStatus($username, $courseId, MinervaStatus::ApplicationReceived);
    }

    public function setApplicationRejected($username, $courseId)
    {
        return $this->setApplicationStatus($username, $courseId, MinervaStatus::ApplicationRejected);
    }

    public function setApplicationRejectedByAcademic($username, $courseId)
    {
        return $this->setApplicationStatus($username, $courseId, MinervaStatus::ApplicationRejectedByAcademic);
    }

    public function setApplicationSentToAcademic($username, $courseId)
    {
        return $this->setApplicationStatus($username, $courseId, MinervaStatus::ApplicationSentToAcademic);
    }

    public function setApplicationSentToApm($username, $courseId)
    {
        return $this->setApplicationStatus($username, $courseId, MinervaStatus::ApplicationSentToApm);
    }

    public function setApplicationWithdrawnByStudent($username, $courseId)
    {
        return $this->setApplicationStatus($username, $courseId, MinervaStatus::ApplicationWithdrawnByStudent);
    }

    public function setBursaryAwarded($username, $courseId)
    {
        return $this->setBursaryStatus($username, $courseId, MinervaStatus::BursaryAwarded);
    }

    public function setBursaryChecked($username, $courseId)
    {
        return $this->setBursaryStatus($username, $courseId, MinervaStatus::BursaryChecked);
    }

    public function setBursaryDeclined($username, $courseId)
    {
        return $this->setBursaryStatus($username, $courseId, MinervaStatus::BursaryDeclined);
    }

    public function setBursaryLapsed($username, $courseId)
    {
        return $this->setBursaryStatus($username, $courseId, MinervaStatus::BursaryLapsed);
    }

    public function setBursaryReceived($username, $courseId)
    {
        return $this->setBursaryStatus($username, $courseId, MinervaStatus::BursaryReceived);
    }

    public function setBursaryRejected($username, $courseId)
    {
        return $this->setBursaryStatus($username, $courseId, MinervaStatus::BursaryRejected);
    }

    public function setEnglishIsFirstLanguage($username, $courseId)
    {
        return $this->setEnglishLanguageStatus($username, $courseId, MinervaStatus::EnglishIsFirstLanguage);
    }

    public function setEnglishIsNotFirstLanguage($username, $courseId)
    {
        return $this->setEnglishLanguageStatus($username, $courseId, MinervaStatus::EnglishIsNotFirstLanguage);
    }

    public function setEnglishLanguageProficiencyProven($username, $courseId)
    {
        return $this->setEnglishLanguageStatus($username, $courseId, MinervaStatus::EnglishLanguageProficiencyProven);
    }

    public function setWaitingForCaeCpeTest($username, $courseId)
    {
        return $this->setEnglishLanguageStatus($username, $courseId, MinervaStatus::WaitingForCaeCpeTest);
    }

    public function setWaitingForIeltsTest($username, $courseId)
    {
        return $this->setEnglishLanguageStatus($username, $courseId, MinervaStatus::WaitingForIeltsTest);
    }

    public function setWaitingForLanguageCentreAssessment($username, $courseId)
    {
        return $this->setEnglishLanguageStatus($username, $courseId, MinervaStatus::WaitingForLanguageCentreAssessment);
    }

    public function setWaitingForToeflTest($username, $courseId)
    {
        return $this->setEnglishLanguageStatus($username, $courseId, MinervaStatus::WaitingForToeflTest);
    }

    /**
     * @param $username
     * @param $courseId
     * @param array $statuses Keys are the Guzzle parameter names and the values are the Minerva status codes
     * @return mixed
     */
    public function createAcademicInformation($username, $courseId, array $statuses)
    {
        $urlParams = array(
            'username' => $username,
            'courseId' => $courseId,
        );

        $params = array_merge($urlParams, $statuses);

        return $this->client
            ->getCommand('CreateAcademicInformation', $params)
            ->execute();
    }

    /**
     * @param $username
     * @param $courseId
     * @param $status
     * @return mixed
     */
    public function setBursaryStatus($username, $courseId, $status)
    {
        return $this->client
            ->getCommand('SetBursaryStatus', array(
            'username' => $username,
            'courseId' => $courseId,
            'status' => $status,
        ))->execute();
    }

    /**
     * @param $username
     * @param $courseId
     * @param $status
     * @return mixed
     */
    public function setApplicationStatus($username, $courseId, $status)
    {
        return $this->client
            ->getCommand('SetApplicationStatus', array(
            'username' => $username,
            'courseId' => $courseId,
            'status' => $status,
        ))->execute();
    }

    /**
     * @param $username
     * @param $courseId
     * @param $status
     * @return mixed
     */
    public function setEnglishLanguageStatus($username, $courseId, $status)
    {
        return $this->client
            ->getCommand('SetEnglishLanguageStatus', array(
            'username' => $username,
            'courseId' => $courseId,
            'status' => $status,
        ))->execute();
    }

    public function getRegistrationStep($username, $courseId, $stepName)
    {
        return $this->client
            ->getCommand('GetRegistrationStep', array(
            'username' => $username,
            'courseId' => $courseId,
            'stepName' => $stepName,
        ))
            ->execute();
    }

    public function setRegistrationStep($username, $courseId, array $values)
    {
        $values = array_merge($values, array(
            'username' => $username,
            'courseId' => $courseId,
        ));

        return $this->client
            ->getCommand('SetRegistrationStep', $values)
            ->execute();
    }

    public function setRegistration($username, $courseId, array $values = array())
    {
        $values = array_merge($values, array(
            'username' => $username,
            'courseId' => $courseId,
        ));

        try{
            return $this->client->getCommand('SetRegistration', $values)->execute();
        }
        catch(\Guzzle\Http\Exception\ClientErrorResponseException $clientErrorResponseException){
            if($clientErrorResponseException->getCode()===400){
                try{
                    $form = $this->serializer->deserialize(
                        $clientErrorResponseException->getResponse()->getBody(true),
                        'Ice\\MinervaClientBundle\\Response\\FormError',
                        'json'
                    );
                }
                catch(\Exception $deserializingException){
                    //We can't improve the exception - just re-throw the original
                    throw $clientErrorResponseException;
                }
                throw new ValidationException($form, 'Validation error', 400, $clientErrorResponseException);
            }
            else{
                throw $clientErrorResponseException;
            }
        }
    }

    public function setRegistrationFieldValue($username, $courseId, $stepName, $fieldName, array $values)
    {
        $values = array_merge($values, array(
            'fieldNameUri' => $fieldName,
            'username' => $username,
            'courseId' => $courseId,
            'stepName' => $stepName,
            'fieldName' => $fieldName,
        ));

        return $this->client
            ->getCommand('SetRegistrationFieldValue', $values)
            ->execute();
    }

    public function addBookingItem($username, $courseId, array $values)
    {
        $values = array_merge($values, array(
            'username' => $username,
            'courseId' => $courseId,
        ));

        return $this->client
            ->getCommand('AddBookingItem', $values)
            ->execute();
    }

    /**
     * @param $iceId
     * @param $courseId
     * @return AcademicInformation
     * @throws \Exception|\Guzzle\Http\Exception\ClientErrorResponseException
     * @throws \Ice\MinervaClientBundle\Exception\NotFoundException
     */
    public function getAcademicInformation($iceId, $courseId){
        try{
            $academicInformation = $this->client->getCommand('GetAcademicInformation', array(
                'username'=>$iceId,
                'courseId'=>$courseId
            ))->execute();
            return $academicInformation;
        }
        catch(\Guzzle\Http\Exception\ClientErrorResponseException $e){
            if($e->getResponse()->getStatusCode()===404){
                throw new NotFoundException("Academic information not found for this person and course", 404, $e);
            }
            else{
                throw $e;
            }
        }
    }

    /**
     * @param $iceId
     * @param $courseId
     * @return AcademicInformation
     * @throws \Exception|\Guzzle\Http\Exception\ClientErrorResponseException
     * @throws \Ice\MinervaClientBundle\Exception\NotFoundException
     */
    public function getAllAcademicInformationByIceId($iceId){
        try{
            $academicInformation = $this->client->getCommand('GetAllAcademicInformation', array(
                'username'=>$iceId
            ))->execute();
            return $academicInformation;
        }
        catch(\Guzzle\Http\Exception\ClientErrorResponseException $e){
            if($e->getResponse()->getStatusCode()===404){
                throw new NotFoundException("Academic information not found for this person and course", 404, $e);
            }
            else{
                throw $e;
            }
        }
    }


    /**
     * @param string $username
     * @param int    $courseId
     * @param array  $values
     *
     * @throws \Exception|\Guzzle\Http\Exception\ClientErrorResponseException
     * @throws \Ice\MinervaClientBundle\Exception\ValidationException
     */
    public function createBooking($username, $courseId, array $values){
        $values = array_merge($values, array(
            'username'=>$username,
            'courseId'=>$courseId,
        ));

        try{
            $this->client->getCommand('CreateBooking', $values)->execute();
        }
        catch(\Guzzle\Http\Exception\ClientErrorResponseException $clientErrorResponseException){
            if($clientErrorResponseException->getCode()===400){
                try{
                    $form = $this->serializer->deserialize(
                        $clientErrorResponseException->getResponse()->getBody(true),
                        'Ice\\MinervaClientBundle\\Response\\FormError',
                        'json'
                    );
                }
                catch(\Exception $deserializingException){
                    //We can't improve the exception - just re-throw the original
                    throw $clientErrorResponseException;
                }
                throw new ValidationException($form, 'Validation error', 400, $clientErrorResponseException);
            }
            else{
                throw $clientErrorResponseException;
            }
        }
    }


    /**
     * Get a booking
     *
     * @param int $id Booking ID
     */
    public function getBooking($id)
    {
        $values = array(
            'id' => $id,
        );

        return $this->client->getCommand('GetBooking', $values)->execute();
    }
}