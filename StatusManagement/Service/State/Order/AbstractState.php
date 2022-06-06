<?php

namespace App\Service\State\Order;

use App\Entity\Courier;
use App\Entity\Order;
use App\Exception\UnknownStatusException;
use App\Manager\CourierManager;
use App\Manager\OrderManager;
use App\Service\CourierStatusChanger;

abstract class AbstractState
{
    /**
     * @var OrderManager
     */
    protected OrderManager $orderManager;

    /**
     * @var CourierManager
     */
    protected CourierManager $courierManager;

    /**
     * @var CourierStatusChanger
     */
    protected CourierStatusChanger $courierStatusChanger;

    /**
     * @param OrderManager         $orderManager
     * @param CourierManager       $courierManager
     * @param CourierStatusChanger $courierStatusChanger
     */
    public function __construct(
        OrderManager $orderManager,
        CourierManager $courierManager,
        CourierStatusChanger $courierStatusChanger
    ) {
        $this->orderManager         = $orderManager;
        $this->courierManager       = $courierManager;
        $this->courierStatusChanger = $courierStatusChanger;
    }

    /**
     * @param Order $order
     *
     * @throws UnknownStatusException
     */
    public function processOrder(Order $order): void
    {
        $order->setStatus($this->getAlias());

        $this->orderManager->save($order);

        /** @var Courier $courier */
        $courier = $order->getCourier();
        $this->courierStatusChanger->setStatus($courier, $this->getCourierStatus($courier));
    }

    /**
     * @param Courier $courier
     *
     * @return string
     */
    abstract public function getCourierStatus(Courier $courier): string;

    /**
     * @return string
     */
    abstract public function getAlias(): string;
}
