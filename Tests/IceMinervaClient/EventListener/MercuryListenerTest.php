<?php

namespace Ice\MinervaClientBundle\Test\EventListener;

use Ice\MercuryBundle\Entity\Suborder;
use Ice\MinervaClientBundle\EventListener\MercuryListener;

class MercuryListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @group mercury
     */
    public function testPaymentMethodOnlineNoPaymentIsStatusArranged()
    {
        $client = $this->getMinervaClient();
        $client
            ->expects($this->atLeastOnce())
            ->method('setPaymentGroupPaymentStatusByReference')
            ->with('IB-123', 'PAYMENT_ARRANGED')
        ;

        $event = $this->getOrderEvent($this->getOrder('ONLINE'));

        $listener = new MercuryListener($client);
        $listener->onCreateOrder($event);
    }

    /**
     * @group mercury
     */
    public function testPaymentMethodInvoiceNoPaymentIsStatusCommitted()
    {
        $client = $this->getMinervaClient();
        $client
            ->expects($this->atLeastOnce())
            ->method('setPaymentGroupPaymentStatusByReference')
            ->with('IB-123', 'PAYMENT_COMMITTED')
        ;

        $event = $this->getOrderEvent($this->getOrder('INVOICE'));

        $listener = new MercuryListener($client);
        $listener->onCreateOrder($event);
    }

    /**
     * @group mercury
     */
    public function testPaymentMethodStudentLoanNoPaymentIsStatusCommitted()
    {
        $client = $this->getMinervaClient();
        $client
            ->expects($this->atLeastOnce())
            ->method('setPaymentGroupPaymentStatusByReference')
            ->with('IB-123', 'PAYMENT_COMMITTED')
        ;

        $event = $this->getOrderEvent($this->getOrder('STUDENT_LOAN'));

        $listener = new MercuryListener($client);
        $listener->onCreateOrder($event);
    }

    /**
     * @group mercury
     */
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
        $client
            ->expects($this->never())
            ->method('setPaymentGroupPaymentStatusByReference')
            ->withAnyParameters()
        ;

        $paymentGroup = $this->getPaymentGroup();
        $paymentGroup
            ->expects($this->any())
            ->method('getAttributeByName')
            ->will($this->returnValueMap($this->getAttributeValueMap('PDQ')))
        ;

        $event = $this->getPaymentGroupEvent($paymentGroup);

        $listener = new MercuryListener($client);
        $listener->onGroupBalanceChange($event);
    }

    /**
     * @group mercury
     */
    public function testPaymentMethodPdqWithPaymentIsPartPaid()
    {
        $client = $this->getMinervaClient();
        $client
            ->expects($this->once())
            ->method('setPaymentGroupPaymentStatusByReference')
            ->with('IB-123', 'PAYMENT_PART_PAID')
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

        $event = $this->getPaymentGroupEvent($paymentGroup);

        $listener = new MercuryListener($client);
        $listener->onGroupBalanceChange($event);
    }

    /**
     * @group mercury
     */
    public function testPaymentMethodChequeFullyPaidIsBalanced()
    {
        $client = $this->getMinervaClient();
        $client
            ->expects($this->once())
            ->method('setPaymentGroupPaymentStatusByReference')
            ->with('IB-123', 'PAYMENT_BALANCED')
        ;

        $paymentGroup = $this->getPaymentGroup('CHEQUE');
        $paymentGroup
            ->expects($this->any())
            ->method('getNetAmountUnallocated')
            ->will($this->returnValue(0))
        ;

        $event = $this->getPaymentGroupEvent($paymentGroup);

        $listener = new MercuryListener($client);
        $listener->onGroupBalanceChange($event);
    }

    /**
     * @group mercury
     */
    public function testPaymentMethodBacsOverpaidIsOverpaid()
    {
        $client = $this->getMinervaClient();
        $client
            ->expects($this->once())
            ->method('setPaymentGroupPaymentStatusByReference')
            ->with('IB-123', 'PAYMENT_OVERPAID')
        ;

        $paymentGroup = $this->getPaymentGroup('BACS');
        $paymentGroup
            ->expects($this->any())
            ->method('getNetAmountUnallocated')
            ->will($this->returnValue(-50))
        ;

        $event = $this->getPaymentGroupEvent($paymentGroup);

        $listener = new MercuryListener($client);
        $listener->onGroupBalanceChange($event);
    }

    /**
     * @group mercury
     */
    public function testBookingOrderReferenceSetWhenOrderCreated()
    {
        $client = $this->getMinervaClient();
        $client
            ->expects($this->exactly(2))
            ->method('setBookingOrderReference')
        ;

        $event = $this->getOrderEvent($this->getOrder('INVOICE'));
        $listener = new MercuryListener($client);
        $listener->onCreateOrder($event);
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

        $paymentGroup
            ->expects($this->any())
            ->method('getExternalId')
            ->will($this->returnValue('IB-123'))
        ;

        return $paymentGroup;
    }

    private function getOrder($secondPaymentType)
    {
        $suborder1 = new Suborder();
        $suborder1->setPaymentGroup($this->getPaymentGroup($secondPaymentType));
        $suborder2 = new Suborder();
        $suborder2->setPaymentGroup($this->getPaymentGroup($secondPaymentType));

        $order = $this->getMock('Ice\MercuryBundle\Entity\Order');
        $order
            ->expects($this->any())
            ->method('getSuborders')
            ->will($this->returnValue(array(
                $suborder1,
                $suborder2,
            )))
        ;

        return $order;
    }

    /**
     * @param $paymentGroup
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getPaymentGroupEvent($paymentGroup)
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
     * @param $order
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getOrderEvent($order)
    {
        $event = $this->getMock('Ice\MercuryBundle\Event\OrderEvent', array(), array($order), '', false);
        $event
            ->expects($this->any())
            ->method('getOrder')
            ->will($this->returnValue($order))
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
        $delegate = $this->getMock('Ice\MercuryBundle\Entity\PaymentGroupAttribute');
        $delegate
            ->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue('abc12'))
        ;

        $course = $this->getMock('Ice\MercuryBundle\Entity\PaymentGroupAttribute');
        $course
            ->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue('1234'))
        ;

        $method = $this->getMock('Ice\MercuryBundle\Entity\PaymentGroupAttribute');
        $method
            ->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue($paymentType))
        ;

        return array(
            array('delegate_ice_id', $delegate),
            array('course_id', $course),
            array('agreed_payment_method', $method),
        );
    }

}
