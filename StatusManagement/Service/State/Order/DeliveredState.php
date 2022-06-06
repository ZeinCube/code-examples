<?php

namespace App\Service\State\Order;

use App\DBAL\Types\CourierStatusType;
use App\DBAL\Types\OrderStatusType;
use App\Entity\Courier;

class DeliveredState extends AbstractState
{
    /**
     * {@inheritDoc}
     */
    public function getAlias(): string
    {
        return OrderStatusType::DELIVERED;
    }

    /**
     * {@inheritDoc}
     */
    public function getCourierStatus(Courier $courier): string
    {
        if ($courier->getStatus() === CourierStatusType::ON_WAY_FINISH) {
            return CourierStatusType::UNAVAILABLE;
        }

        return CourierStatusType::FREE;
    }
}
