<?php

namespace Ice\MinervaClientBundle\Service;

use Guzzle\Service\Client;

use Ice\MinervaClientBundle\Entity\MinervaStatus;

class MinervaClient
{
    /**
     * @var \Guzzle\Service\Client
     */
    private $client;

    /**
     * @param \Guzzle\Service\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->client->setDefaultHeaders(array(
            'Accepts' => 'application/json',
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
            'bursaryStatusCode' => $status,
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
            'applicationStatusCode' => $status,
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
            'englishLanguageStatusCode' => $status,
        ))->execute();
    }
}