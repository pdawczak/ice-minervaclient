<?php
namespace Ice\MinervaClientBundle\EventListener;

use Guzzle\Http\Exception\BadResponseException;
use Ice\MercuryBundle\Event\DeleteOrderEvent;
use Ice\MercuryBundle\Event\OrderEvent;
use Ice\MercuryBundle\Event\PaymentGroupEvent;
use Ice\MinervaClientBundle\Entity\MinervaStatus;
use Ice\MinervaClientBundle\Service\MinervaClient;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MercuryListener implements EventSubscriberInterface
{
    /** @var \Ice\MinervaClientBundle\Service\MinervaClient */
    private $minervaClient;

    /** @var  Logger */
    private $logger;

    /**
     * @param \Ice\MinervaClientBundle\Service\MinervaClient $minervaClient
     * @param Logger $logger
     */
    public function __construct(MinervaClient $minervaClient, $logger = null)
    {
        $this->logger = $logger;
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

        try {
            if (0 === $group->getNetAmountUnallocated()) {
                $this->minervaClient->setPaymentGroupPaymentStatusByReference($group->getExternalId(), MinervaStatus::PaymentBalanced);
            } elseif (
                $group->getNetAmountAllocated() > 0 &&
                $group->getNetAmountAllocated() < $group->getNetAmount()
            ) {
                $this->minervaClient->setPaymentGroupPaymentStatusByReference($group->getExternalId(), MinervaStatus::PaymentPartPaid);
            } elseif ($group->getNetAmountUnallocated() < 0) {
                $this->minervaClient->setPaymentGroupPaymentStatusByReference($group->getExternalId(), MinervaStatus::PaymentOverpaid);
            }
        } catch (\Exception $e) {
            $this->logException($e, "handle balance change of group ".$group->getId());
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
        $reference = $event->getOrder()->getReference();

        foreach ($suborders as $suborder) {
            try {
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

                $this->minervaClient->setBookingOrderReference($username, $courseId, $reference);

                // It is assumed that invoices and student loans (and others, see WPMR-236) will be paid so a place is allocated.
                // Other payment methods will be held for the appropriate amount of time and lost, unless payment is received.
                if (in_array($paymentMethod, array('INVOICE', 'STUDENT_LOAN', 'CHEQUE', 'BACS', 'PDQ'))) {
                    $this->minervaClient->setPaymentGroupPaymentStatusByReference($group->getExternalId(), MinervaStatus::PaymentCommitted);
                } else {
                    $this->minervaClient->setPaymentGroupPaymentStatusByReference($group->getExternalId(), MinervaStatus::PaymentArranged);
                }
            } catch (\Exception $e) {
                $this->logException($e, "handle creation of suborder ".$suborder->getId());
            }
        }
    }

    /**
     * Fired just before the deletion of an order is flushed
     *
     * @param DeleteOrderEvent $event
     */
    public function onDeleteOrder(DeleteOrderEvent $event)
    {
        $network = $event->getOrderNetwork();
        foreach ($network->getPaymentGroupsInNetwork() as $paymentGroup) {
            try {
                $this->minervaClient->setPaymentGroupPaymentStatusByReference($paymentGroup->getExternalId(), null);
            } catch (\Exception $e) {
                $this->logException($e, "handle deletion of payment group ".$paymentGroup->getExternalId());
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
            'mercury.delete_order' => 'onDeleteOrder',
        );
    }

    /**
     * Logs information about an exception if a logger is available
     *
     * @param \Exception $e
     * @param $intention
     */
    private function logException(\Exception $e, $intention)
    {
        if (!$this->logger) {
            return;
        }

        $context = [
            'message' => $e->getMessage()
        ];

        if ($e instanceof BadResponseException) {
            $context['request'] = $e->getRequest()->__toString();
            $context['response'] = $e->getResponse()->__toString();
        }

        $this->logger->error("Exception encountered when trying to ".$intention, $context);
    }
}