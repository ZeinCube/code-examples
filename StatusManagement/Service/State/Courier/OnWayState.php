<?php

namespace App\Service\State\Courier;

use App\DBAL\Types\CourierStatusType;
use App\Entity\Courier;

class OnWayState extends AbstractState
{
    /**
     * {@inheritDoc}
     */
    public function getAlias(): string
    {
        return CourierStatusType::ON_WAY;
    }

    /**
     * {@inheritDoc}
     */
    protected function unavailable(Courier $courier): string
    {
        return CourierStatusType::ON_WAY_FINISH;
    }

    /**
     * {@inheritDoc}
     */
    protected function free(Courier $courier): string
    {
        if ($this->orderManager->hasActiveOrders($courier)) {
            return $this->getAlias();
        }

        return CourierStatusType::FREE;
    }

    /**
     * {@inheritDoc}
     */
    protected function getAvailableTransitions(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    protected function cancelOrder(Courier $courier): string
    {
        return $this->free($courier);
    }
}
