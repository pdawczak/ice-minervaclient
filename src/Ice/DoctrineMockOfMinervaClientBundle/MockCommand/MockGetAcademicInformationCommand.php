<?php

namespace Ice\DoctrineMockOfMinervaClientBundle\MockCommand;

use Guzzle\Service\Exception\CommandException;
use Ice\MinervaClientBundle\Entity\Course;
use Doctrine\ORM\EntityRepository;

class MockGetAcademicInformationCommand extends AbstractMockCommand
{
    /**
     * @var EntityRepository;
     */
    private $aiRepository;

    /**
     * @var array
     */
    private $args;

    public function __construct(
        $aiRepository,
        $args
    )
    {
        $this->args = $args;
        $this->aiRepository = $aiRepository;
    }

    /**
     * Execute the command and return the result
     *
     * @return mixed Returns the result of {@see CommandInterface::execute}
     * @throws CommandException if a client has not been associated with the command
     */
    public function execute()
    {
        return $this->aiRepository->find([
            'iceId' => $this->args['username'],
            'courseId' => $this->args['courseId']
        ]);
    }
}
