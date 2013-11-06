<?php

namespace Ice\MinervaClientBundle\Test\Service;

use Guzzle\Service\Description\ServiceDescription;
use Guzzle\Tests\GuzzleTestCase;


class MinervaClientTest extends GuzzleTestCase
{
    protected function setUp()
    {
        $this->setServiceBuilder(\Guzzle\Service\Builder\ServiceBuilder::factory(array(
            'minerva.client' => array(
                'class' => 'Guzzle\Service\Client',
            )
        )));
        $this->getServiceBuilder()->get('minerva.client')
            ->setDescription(ServiceDescription::factory(__DIR__.'/../../../Resources/config/client.json'));
    }

    /**
     * @group guzzle
     */
    public function testSetPaymentGroupPaymentStatusByReference()
    {
        $client = $this->getServiceBuilder()->get('minerva.client');

        $command = $client->getCommand('SetPaymentGroupPaymentStatusByReference', array(
            'reference' => 'IB-123',
            'status' => 'NewStatus'
        ));

        $command->prepare();

        $this->assertEquals('"NewStatus"', $command->getRequest()->getBody()->__toString());
        $this->assertEquals('application/json', $command->getRequest()->getHeader('Content-type'));
    }
}