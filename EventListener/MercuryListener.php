<?php
namespace Ice\MinervaClientBundle\EventListener;

use Ice\MercuryBundle\Event\GroupBalanceChangeEvent;
use Ice\MinervaClientBundle\Service\MinervaClient;

class MercuryListener{
    /** @var \Ice\MinervaClientBundle\Service\MinervaClient */
    private $minervaClient;

    /**
     * @param \Ice\MinervaClientBundle\Service\MinervaClient $minervaClient
     */
    public function __construct(MinervaClient $minervaClient){
        $this->minervaClient = $minervaClient;
    }

    /**
     * @return \Ice\MinervaClientBundle\Service\MinervaClient
     */
    protected function getMinervaClient(){
        return $this->minervaClient;
    }

    /**
     * The balance of a suborder group has changed, either because a Transaction has been allocated or because
     * the amount receivable has been amended. The payment status of the booking may need to change.
     *
     * @param $event
     */
    public function onGroupBalanceChange(GroupBalanceChangeEvent $event){
        //TODO
    }
}