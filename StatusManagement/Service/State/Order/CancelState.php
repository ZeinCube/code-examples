<?php

namespace App\Service\State\Order;

use App\DBAL\Types\OrderStatusType;
use App\Entity\Courier;
use App\Entity\Order;
use App\Service\State\Courier\AbstractState as CourierAbstractState;

class CancelState extends AbstractState
{
    /**
     * {@inheritDoc}
     */
    public function processOrder(Order $order): void
    {
        if ($order->getStatus() === OrderStatusType::DELIVERED) {
            return;
        }

        parent::processOrder($order);
    }

    /**
     * {@inheritDoc}
     */
    public function getAlias(): string
    {
        return OrderStatusType::CANCELED;
    }

    /**
     * {@inheritDoc}
     */
    public function getCourierStatus(Courier $courier): string
    {
        return CourierAbstractState::ORDER_CANCELED;
    }
}
