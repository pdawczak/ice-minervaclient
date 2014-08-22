<?php

namespace Ice\DoctrineMockOfMinervaClientBundle\MockClient;

use Ice\DoctrineMockOfMinervaClientBundle\Exception\CommandNotImplementedException;
use Ice\DoctrineMockOfMinervaClientBundle\MockClient\AbstractGuzzleClient;
use Ice\DoctrineMockOfMinervaClientBundle\MockCommand\MockGetAcademicInformationCommand;
use Ice\DoctrineMockOfMinervaClientBundle\MockCommand\MockGetCourseCommand;
use Doctrine\ORM\EntityManager;

class MockGuzzleClient extends AbstractGuzzleClient
{
    /**
     * @param EntityManager $entityManager
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setDefaultHeaders($headers)
    {
        //Ignore
    }

    public function setConfig($config)
    {
        //Ignore
    }

    public function getCommand($name, array $args = array())
    {
        switch ($name) {
            case 'GetAcademicInformation':
                return new MockGetAcademicInformationCommand(
                    $this->getAcademicInformationRepository(),
                    $args
                );
                break;
            default:
                throw new CommandNotImplementedException('Command: '.$name.' is not supported');
        }
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getAcademicInformationRepository()
    {
        return $this->entityManager->getRepository('IceDoctrineMockOfMinervaClientBundle:AcademicInformation');
    }
}
