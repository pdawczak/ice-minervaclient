<?php
namespace Ice\MinervaClientBundle\EventListener;

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

        $username = $group->getAttributeByName('delegate_ice_id');
        $courseId = $group->getAttributeByName('course_id');
        $paymentMethod = $group->getAttributeByName('agreed_payment_method');

        if (0 === $group->getNetAmountUnallocated()) {
            $this->minervaClient->bookingPaymentBalanced($username, $courseId);
        } elseif (
            $group->getNetAmountAllocated() > 0 &&
            $group->getNetAmountAllocated() < $group->getNetAmount()
        ) {
            $this->minervaClient->bookingPaymentPart($username, $courseId);
        } elseif ($group->getNetAmountUnallocated() < 0) {
            $this->minervaClient->bookingPaymentOverpaid($username, $courseId);
        } elseif (in_array($paymentMethod, array('INVOICE', 'STUDENT_LOAN'))) {
            $this->minervaClient->bookingPaymentArranged($username, $courseId);
        }
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            'mercury.post_group_balance_change' => 'onGroupBalanceChange',
        );
    }
}