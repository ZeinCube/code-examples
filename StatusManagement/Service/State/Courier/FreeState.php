<?php

namespace App\Service\State\Courier;

use App\DBAL\Types\CourierStatusType;
use App\Entity\Courier;

class FreeState extends AbstractState
{
    /**
     * {@inheritDoc}
     */
    public function getAlias(): string
    {
        return CourierStatusType::FREE;
    }

    /**
     * {@inheritDoc}
     */
    protected function unavailable(Courier $courier): string
    {
        if ($this->orderManager->hasActualOrders($courier)) {
            return CourierStatusType::FREE_FINISH;
        }

        return CourierStatusType::UNAVAILABLE;
    }

    /**
     * {@inheritDoc}
     */
    protected function free(Courier $courier): string
    {
        return $this->getAlias();
    }

    /**
     * {@inheritDoc}
     */
    protected function getAvailableTransitions(): array
    {
        return [
            CourierStatusType::ON_WAY => CourierStatusType::ON_WAY,
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function cancelOrder(Courier $courier): string
    {
        return $this->getAlias();
    }
}
