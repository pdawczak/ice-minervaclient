<?php

namespace Ice\MinervaClientBundle\Test\Service;

use Ice\MinervaClientBundle\EventListener\MercuryListener;

class MercuryListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testPaymentMethodInvoiceNoPaymentIsStatusArranged()
    {
        $client = $this->getMinervaClient();
        $client
            ->expects($this->once())
            ->method('bookingPaymentArranged')
        ;

        $paymentGroup = $this->getPaymentGroup('INVOICE');

        $event = $this->getEvent($paymentGroup);

        $listener = new MercuryListener($client);
        $listener->onGroupBalanceChange($event);
    }

    public function testPaymentMethodStudentLoanNoPaymentIsStatusArranged()
    {
        $client = $this->getMinervaClient();
        $client
            ->expects($this->once())
            ->method('bookingPaymentArranged')
        ;

        $paymentGroup = $this->getPaymentGroup('STUDENT_LOAN');

        $event = $this->getEvent($paymentGroup);

        $listener = new MercuryListener($client);
        $listener->onGroupBalanceChange($event);
    }

    public function testPaymentMethodPdqNoPaymentIsNoOp()
    {
        $client = $this->getMinervaClient();
        $client
            ->expects($this->never())
            ->method('bookingPaymentBalanced')
        ;
        $client
            ->expects($this->never())
            ->method('bookingPaymentPart')
        ;
        $client
            ->expects($this->never())
            ->method('bookingPaymentOverpaid')
        ;
        $client
            ->expects($this->never())
            ->method('bookingPaymentArranged')
        ;

        $paymentGroup = $this->getPaymentGroup();
        $paymentGroup
            ->expects($this->any())
            ->method('getAttributeByName')
            ->will($this->returnValueMap($this->getAttributeValueMap('PDQ')))
        ;

        $event = $this->getEvent($paymentGroup);

        $listener = new MercuryListener($client);
        $listener->onGroupBalanceChange($event);
    }

    public function testPaymentMethodPdqWithPaymentIsPartPaid()
    {
        $client = $this->getMinervaClient();
        $client
            ->expects($this->once())
            ->method('bookingPaymentPart')
        ;

        $paymentGroup = $this->getPaymentGroup('PDQ');
        $paymentGroup
            ->expects($this->any())
            ->method('getNetAmountAllocated')
            ->will($this->returnValue(50))
        ;
        $paymentGroup
            ->expects($this->any())
            ->method('getNetAmount')
            ->will($this->returnValue(150))
        ;

        $event = $this->getEvent($paymentGroup);

        $listener = new MercuryListener($client);
        $listener->onGroupBalanceChange($event);
    }

    public function testPaymentMethodChequeFullyPaidIsBalanced()
    {
        $client = $this->getMinervaClient();
        $client
            ->expects($this->once())
            ->method('bookingPaymentBalanced')
        ;

        $paymentGroup = $this->getPaymentGroup('CHEQUE');
        $paymentGroup
            ->expects($this->any())
            ->method('getNetAmountUnallocated')
            ->will($this->returnValue(0))
        ;

        $event = $this->getEvent($paymentGroup);

        $listener = new MercuryListener($client);
        $listener->onGroupBalanceChange($event);
    }

    public function testPaymentMethodBacsOverpaidIsOverpaid()
    {
        $client = $this->getMinervaClient();
        $client
            ->expects($this->once())
            ->method('bookingPaymentOverpaid')
        ;

        $paymentGroup = $this->getPaymentGroup('BACS');
        $paymentGroup
            ->expects($this->any())
            ->method('getNetAmountUnallocated')
            ->will($this->returnValue(-50))
        ;

        $event = $this->getEvent($paymentGroup);

        $listener = new MercuryListener($client);
        $listener->onGroupBalanceChange($event);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMinervaClient()
    {
        $metadataFactory = $this->getMock('Metadata\MetadataFactoryInterface');
        $handlerRegistry = $this->getMock('JMS\Serializer\Handler\HandlerRegistry');
        $objectConstructor = $this->getMock('JMS\Serializer\Construction\ObjectConstructorInterface');
        $map = $this->getMock('PhpCollection\Map');
        $serializer = $this->getMock('JMS\Serializer\Serializer', array(), array($metadataFactory, $handlerRegistry, $objectConstructor, $map, $map), '', false);
        $guzzle = $this->getMock('Guzzle\Service\Client');
        $client = $this->getMock('Ice\MinervaClientBundle\Service\MinervaClient', array(), array($guzzle, $serializer, '', ''), '', false);

        return $client;
    }

    /**
     * @param string $paymentType
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getPaymentGroup($paymentType = 'MOTO')
    {
        $paymentGroup = $this->getMock('Ice\MercuryBundle\Entity\PaymentGroup');
        $paymentGroup
            ->expects($this->any())
            ->method('getAttributeByName')
            ->will($this->returnValueMap($this->getAttributeValueMap($paymentType)))
        ;

        return $paymentGroup;
    }

    /**
     * @param $paymentGroup
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getEvent($paymentGroup)
    {
        $event = $this->getMock('Ice\MercuryBundle\Event\PaymentGroupEvent', array(), array($paymentGroup), '', false);
        $event
            ->expects($this->any())
            ->method('getPaymentGroup')
            ->will($this->returnValue($paymentGroup))
        ;

        return $event;
    }

    /**
     * @param $paymentType
     *
     * @return array
     */
    private function getAttributeValueMap($paymentType)
    {
        return array(
            array('delegate_ice_id', 'abc12'),
            array('course_id', 1234),
            array('agreed_payment_method', $paymentType),
        );
    }

}
