<?php

namespace App\Service\State\Courier;

use App\DBAL\Types\CourierStatusType;
use App\Entity\Courier;

class OnWayFinishState extends AbstractState
{
    /**
     * {@inheritDoc}
     */
    public function getAlias(): string
    {
        return CourierStatusType::ON_WAY_FINISH;
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
        return CourierStatusType::ON_WAY;
    }

    /**
     * {@inheritDoc}
     */
    protected function getAvailableTransitions(): array
    {
        return [
            CourierStatusType::FREE_FINISH => CourierStatusType::FREE_FINISH,
            CourierStatusType::ON_WAY      => CourierStatusType::FREE_FINISH,
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function cancelOrder(Courier $courier): string
    {
        if ($this->orderManager->hasActiveOrders($courier)) {
            return CourierStatusType::ON_WAY_FINISH;
        }

        return $this->unavailable($courier);
    }
}
