<?php

namespace App\Service\State\Order;

use App\DBAL\Types\OrderStatusType;
use App\Entity\Courier;

class PickedState extends AbstractState
{
    /**
     * {@inheritDoc}
     */
    public function getAlias(): string
    {
        return OrderStatusType::PICKED;
    }

    /**
     * {@inheritDoc}
     */
    public function getCourierStatus(Courier $courier): string
    {
        return $courier->getStatus();
    }
}
