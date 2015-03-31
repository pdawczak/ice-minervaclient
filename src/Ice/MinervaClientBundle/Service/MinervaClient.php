<?php

namespace Ice\MinervaClientBundle\Service;

use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Service\ClientInterface;

use Ice\MinervaClientBundle\Entity\AcademicInformation;
use Ice\MinervaClientBundle\Entity\BookingItemSummary;
use Ice\MinervaClientBundle\Entity\CourseApplication;
use Ice\MinervaClientBundle\Entity\CourseApplicationStep;
use Ice\MinervaClientBundle\Entity\MinervaStatus;
use Ice\MinervaClientBundle\Exception\ClientErrorResponseException;
use Ice\MinervaClientBundle\Exception\NotFoundException;
use Ice\MinervaClientBundle\Exception\ValidationException;
use Ice\MinervaClientBundle\Entity\Booking;
use JMS\Serializer\Serializer;


class MinervaClient
{
    /**
     * @var \Guzzle\Service\ClientInterface
     */
    private $client;

    /**
     * @var \JMS\Serializer\Serializer
     */
    private $serializer;

    /**
     * @param ClientInterface     $client
     * @param Serializer $serializer
     * @param string     $username
     * @param string     $password
     */
    public function __construct(ClientInterface $client, Serializer $serializer, $username, $password)
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
     * @throws BadResponseException
     * @throws \Ice\MinervaClientBundle\Exception\NotFoundException
     */
    public function getAcademicInformation($iceId, $courseId){
        try{
            $command = $this->client->getCommand('GetAcademicInformation', array(
                'username'=>$iceId,
                'courseId'=>$courseId
            ));

            $academicInformation = $command->execute();

            if($academicInformation instanceof AcademicInformation) {
                return $academicInformation;
            }
            else {
                $e = new BadResponseException("Response cannot be parsed into an AcademicInformation");
                $e->setResponse($command->getResponse());
                $e->setRequest($command->getRequest());
                throw $e;
            }
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
     * @return AcademicInformation[]
     * @throws \Exception|\Guzzle\Http\Exception\ClientErrorResponseException
     * @throws \Ice\MinervaClientBundle\Exception\NotFoundException
     */
    public function getAllAcademicInformationByIceId($iceId){
        try{
            $academicInformation = $this->client->getCommand('GetAllAcademicInformationByPerson', array(
                'username'=>$iceId
            ))->execute();
            return $academicInformation;
        }
        catch(\Guzzle\Http\Exception\ClientErrorResponseException $e){
            if($e->getResponse()->getStatusCode()===404){
                throw new NotFoundException("Academic information not found for this person", 404, $e);
            }
            else{
                throw $e;
            }
        }
    }

    /**
     * @param $courseId
     * @return AcademicInformation[]
     * @throws \Exception|\Guzzle\Http\Exception\ClientErrorResponseException
     * @throws \Ice\MinervaClientBundle\Exception\NotFoundException
     */
    public function getAllAcademicInformationByCourseId($courseId){
        try{
            $academicInformation = $this->client->getCommand('GetAllAcademicInformationByCourse', array(
                'courseId'=>$courseId
            ))->execute();
            return $academicInformation;
        }
        catch(\Guzzle\Http\Exception\ClientErrorResponseException $e){
            if($e->getResponse()->getStatusCode()===404){
                throw new NotFoundException("Academic information not found for this person", 404, $e);
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
            $this->client->getCommand('PutBooking', $values)->execute();
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
     * @param $username
     * @param $courseId
     * @param Booking $booking
     * @throws \Exception|\Guzzle\Http\Exception\ClientErrorResponseException
     * @throws \Ice\MinervaClientBundle\Exception\ValidationException
     */
    public function updateBooking($username, $courseId, Booking $booking) {
        $values = array(
            'username'=>$username,
            'courseId'=>$courseId,
            'bookedBy'=>$booking->getBookedBy(),
            'bookingDate'=>$booking->getBookingDate() ? $booking->getBookingDate()->format('c') : null,
            'orderReference'=>$booking->getOrderReference(),
            'reference'=>$booking->getBookingReference(),
            'bookingItems'=>[]
        );

        foreach($booking->getBookingItems() as $bookingItem){
            $values['bookingItems'][] = [
                'code'=>$bookingItem->getCode(),
                'description'=>$bookingItem->getDescription(),
                'price'=>$bookingItem->getPrice(),
                'category'=>$bookingItem->getCategory()->getId(),
            ];
        }

        try{
            $this->client->getCommand('PutBooking', $values)->execute();
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

    /**
     * Cancel a booking
     *
     * @param $username
     * @param $courseId
     *
     * @return mixed
     */
    public function cancelBooking($username, $courseId)
    {
        $values = array(
            'username' => $username,
            'courseId' => $courseId,
        );

        return $this->client->getCommand('CancelBooking', $values)->execute();
    }

    /**
     * @deprecated See setPaymentGroupPaymentStatusByReference
     *
     * @param $username
     * @param $courseId
     * @return mixed
     */
    public function bookingPaymentNull($username, $courseId)
    {
        $values = array(
            'username' => $username,
            'courseId' => $courseId,
        );

        return $this->client->getCommand('BookingPaymentNull', $values)->execute();
    }

    /**
     * @deprecated See setPaymentGroupPaymentStatusByReference
     *
     * @param $username
     * @param $courseId
     * @return mixed
     */
    public function bookingPaymentArranged($username, $courseId)
    {
        $values = array(
            'username' => $username,
            'courseId' => $courseId,
        );

        return $this->client->getCommand('BookingPaymentArranged', $values)->execute();
    }

    /**
     * @deprecated See setPaymentGroupPaymentStatusByReference
     *
     * @param $username
     * @param $courseId
     * @return mixed
     */
    public function bookingPaymentCommitted($username, $courseId)
    {
        $values = array(
            'username' => $username,
            'courseId' => $courseId,
        );

        return $this->client->getCommand('BookingPaymentCommitted', $values)->execute();
    }

    /**
     * @deprecated See setPaymentGroupPaymentStatusByReference
     *
     * @param $username
     * @param $courseId
     * @return mixed
     */
    public function bookingPaymentPart($username, $courseId)
    {
        $values = array(
            'username' => $username,
            'courseId' => $courseId,
        );

        return $this->client->getCommand('BookingPaymentPart', $values)->execute();
    }

    /**
     * @deprecated See setPaymentGroupPaymentStatusByReference
     *
     * @param $username
     * @param $courseId
     * @return mixed
     */
    public function bookingPaymentBalanced($username, $courseId)
    {
        $values = array(
            'username' => $username,
            'courseId' => $courseId,
        );

        return $this->client->getCommand('BookingPaymentBalanced', $values)->execute();
    }

    /**
     * @deprecated See setPaymentGroupPaymentStatusByReference
     *
     * @param $username
     * @param $courseId
     * @return mixed
     */
    public function bookingPaymentOverpaid($username, $courseId)
    {
        $values = array(
            'username' => $username,
            'courseId' => $courseId,
        );

        return $this->client->getCommand('BookingPaymentOverpaid', $values)->execute();
    }

    public function setPaymentGroupPaymentStatusByReference($reference, $status)
    {
        $values = array(
            'reference' => $reference,
            'status' =>$status
        );

        return $this->client->getCommand('SetPaymentGroupPaymentStatusByReference', $values)->execute();
    }

    /**
     * @param $code
     *
     * @return BookingItemSummary
     */
    public function getBookingItemSummary($code)
    {
        $values = array(
            'code' => $code,
        );

        return $this->client->getCommand('GetBookingItemSummary', $values)->execute();
    }

    /**
     * @param $username
     * @param $courseId
     * @param $reference
     *
     * @return Booking
     */
    public function setBookingOrderReference($username, $courseId, $reference)
    {
        $values = array(
            'username' => $username,
            'courseId' => $courseId,
            'reference' => $reference
        );

        return $this->client->getCommand('SetBookingOrderReference', $values)->execute();
    }

    /**
     * Create a new course application against an academic information specified by the username and course ID.
     * If the academic information does not exist, it will be created in the same transaction.
     *
     * All steps which will form part of the application should be specified on the CourseApplication object.
     *
     * @param $username
     * @param $courseId
     * @param CourseApplication $courseApplication
     * @return mixed
     */
    public function beginCourseApplication($username, $courseId, CourseApplication $courseApplication)
    {
        $values = array(
            'username' => $username,
            'courseId' => $courseId,
            'courseApplicationSteps' => []
        );

        foreach ($courseApplication->getCourseApplicationSteps() as $step) {
            $fieldValues = [];

            foreach ($step->getFieldValues() as $fieldValue) {
                $fieldValues[] = [
                    'fieldName' => $fieldValue->getFieldName(),
                    'description' => $fieldValue->getDescription(),
                    'value' => $fieldValue->getValueSerialized(),
                    'order' => $fieldValue->getOrder()
                ];
            }

            $values['courseApplicationSteps'][] = [
                'stepName' => $step->getStepName(),
                'complete' => $step->isComplete(),
                'stepVersion' => $step->getStepVersion(),
                'description' => $step->getDescription(),
                'order' => $step->getOrder(),
                'courseApplicationFieldValues' => $fieldValues
            ];
        }

        return $this->client->getCommand('PostCourseApplication', $values)->execute();
    }

    /**
     * Replace the collection of field values with those specified by the given CourseApplicationStep, and set the
     * remote step to complete based on whether the completion date is set on the passed entity.
     *
     * @param $applicationId
     * @param CourseApplicationStep $courseApplicationStep
     * @return mixed
     */
    public function updateCourseApplicationStep($applicationId, CourseApplicationStep $courseApplicationStep)
    {
        $values = array(
            'id' => $applicationId,
            'complete' => $courseApplicationStep->isComplete(),
            'stepName' => $courseApplicationStep->getStepName(),
            'courseApplicationFieldValues' => []
        );

        foreach ($courseApplicationStep->getFieldValues() as $value) {
            $values['courseApplicationFieldValues'][] = [
                'fieldName' => $value->getFieldName(),
                'description' => $value->getDescription(),
                'value' => $value->getValueSerialized(),
                'order' => $value->getOrder()
            ];
        }

        return $this->client->getCommand('UpdateCourseApplicationStep', $values)->execute();
    }

    /**
     * Get a course application by its ID
     *
     * @param $id
     * @return mixed
     */
    public function getCourseApplication($id)
    {
        $values = array(
            'id' => $id
        );
        return $this->client->getCommand('GetCourseApplication', $values)->execute();
    }
}
