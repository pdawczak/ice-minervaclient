<?php
namespace Ice\MinervaClientBundle\EventListener;

use Guzzle\Http\Exception\BadResponseException;
use Ice\MercuryBundle\Event\OrderEvent;
use Ice\MercuryBundle\Event\PaymentGroupEvent;
use Ice\MinervaClientBundle\Service\MinervaClient;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MercuryListener implements EventSubscriberInterface
{
    /** @var \Ice\MinervaClientBundle\Service\MinervaClient */
    private $minervaClient;

    /**
     * @param \Ice\MinervaClientBundle\Service\MinervaClient $minervaClient
     */
    public function __construct(MinervaClient $minervaClient)
    {
        $this->minervaClient = $minervaClient;
    }

    /**
     * @return \Ice\MinervaClientBundle\Service\MinervaClient
     */
    protected function getMinervaClient()
    {
        return $this->minervaClient;
    }

    /**
     * The balance of a suborder group has changed, either because a Transaction has been allocated or because
     * the amount receivable has been amended. The payment status of the booking may need to change.
     *
     * @param $event
     */
    public function onGroupBalanceChange(PaymentGroupEvent $event)
    {
        $group = $event->getPaymentGroup();

        $username = $group->getAttributeByName('delegate_ice_id')->getValue();
        $courseId = $group->getAttributeByName('course_id')->getValue();

        if (0 === $group->getNetAmountUnallocated()) {
            $this->minervaClient->bookingPaymentBalanced($username, $courseId);
        } elseif (
            $group->getNetAmountAllocated() > 0 &&
            $group->getNetAmountAllocated() < $group->getNetAmount()
        ) {
            $this->minervaClient->bookingPaymentPart($username, $courseId);
        } elseif ($group->getNetAmountUnallocated() < 0) {
            $this->minervaClient->bookingPaymentOverpaid($username, $courseId);
        }
    }

    /**
     * An order has been created. This could be for a new booking or an amendment.
     *
     * @param OrderEvent $event
     */
    public function onCreateOrder(OrderEvent $event)
    {
        $suborders = $event->getOrder()->getSuborders();

        foreach ($suborders as $suborder) {
            $group = $suborder->getPaymentGroup();
            $numberOfSuborders = count($group->getSuborders());

            if ($numberOfSuborders > 1) {
                // Payment has already been made on this suborder.
                // The new order is for an amendment so we don't need to update the payment status in Minerva
                return;
            }

            $username = $group->getAttributeByName('delegate_ice_id')->getValue();
            $courseId = $group->getAttributeByName('course_id')->getValue();
            $paymentMethod = $group->getAttributeByName('agreed_payment_method')->getValue();

            // It is assumed that invoices and student loans will be paid so a place is allocated.
            // Other payment methods will be held for the appropriate amount of time and lost, unless payment is received.
            if (in_array($paymentMethod, array('INVOICE', 'STUDENT_LOAN'))) {
                $this->minervaClient->bookingPaymentCommitted($username, $courseId);
            } else {
                $this->minervaClient->bookingPaymentArranged($username, $courseId);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            'mercury.post_group_balance_change' => 'onGroupBalanceChange',
            'mercury.post_create_order' => 'onCreateOrder',
        );
    }
}