<?php

namespace App\Service\State\Courier;

use App\DBAL\Types\CourierStatusType;
use App\Entity\Courier;

class FreeFinishState extends AbstractState
{
    /**
     * {@inheritDoc}
     */
    public function getAlias(): string
    {
        return CourierStatusType::FREE_FINISH;
    }

    /**
     * {@inheritDoc}
     */
    protected function unavailable(Courier $courier): string
    {
        return $this->getAlias();
    }

    /**
     * {@inheritDoc}
     */
    protected function free(Courier $courier): string
    {
        return CourierStatusType::FREE;
    }

    /**
     * {@inheritDoc}
     */
    protected function getAvailableTransitions(): array
    {
        return [
            CourierStatusType::ON_WAY => CourierStatusType::ON_WAY_FINISH,
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
