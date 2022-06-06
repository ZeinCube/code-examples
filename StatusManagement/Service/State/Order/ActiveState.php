<?php

namespace App\Service\State\Order;

use App\DBAL\Types\CourierStatusType;
use App\DBAL\Types\OrderStatusType;
use App\Entity\Courier;
use App\Entity\Order;

class ActiveState extends AbstractState
{
    /**
     * {@inheritDoc}
     */
    public function processOrder(Order $order): void
    {
        $this->orderManager->changeActiveToPicked();

        parent::processOrder($order);
    }

    /**
     * {@inheritDoc}
     */
    public function getAlias(): string
    {
        return OrderStatusType::ACTIVE;
    }

    /**
     * {@inheritDoc}
     */
    public function getCourierStatus(Courier $courier): string
    {
        return CourierStatusType::ON_WAY;
    }
}
