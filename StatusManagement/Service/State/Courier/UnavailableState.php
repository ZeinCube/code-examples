<?php

namespace App\Service\State\Courier;

use App\DBAL\Types\CourierStatusType;
use App\Entity\Courier;

class UnavailableState extends AbstractState
{
    /**
     * {@inheritDoc}
     */
    public function processCourier(Courier $courier): void
    {
        $activeOrders = $this->orderManager->getActualOrders($courier);

        if (count($activeOrders) > 0) {
            return;
        }

        $courier->setStatus(CourierStatusType::UNAVAILABLE);
        $this->courierManager->save($courier);
    }

    /**
     * {@inheritDoc}
     */
    public function getAlias(): string
    {
        return CourierStatusType::UNAVAILABLE;
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
        return [];
    }

    /**
     * {@inheritDoc}
     */
    protected function cancelOrder(Courier $courier): string
    {
        return $this->getAlias();
    }
}
