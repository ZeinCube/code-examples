<?php

namespace App\Service\State\Order;

use App\DBAL\Types\OrderStatusType;
use App\Entity\Order;
use App\Exception\UnknownStatusException;

class StateFactory
{
    private const STATUS_TRANSITION_MAP = [
        OrderStatusType::ACCEPTED => OrderStatusType::PICKED,
        OrderStatusType::PICKED   => OrderStatusType::ACTIVE,
        OrderStatusType::ACTIVE   => OrderStatusType::DELIVERED,
    ];

    /**
     * @var AbstractState[]
     */
    private array $states;

    /**
     * @param AbstractState[] $states
     */
    public function __construct(iterable $states)
    {
        foreach ($states as $state) {
            $this->states[$state->getAlias()] = $state;
        }
    }

    /**
     * @param Order $order
     *
     * @throws UnknownStatusException
     *
     * @return AbstractState
     */
    public function getNextState(Order $order): AbstractState
    {
        if (!isset(static::STATUS_TRANSITION_MAP[$order->getStatus()])) {
            throw new UnknownStatusException($order->getStatus(), $order->getStatus());
        }

        return $this->states[static::STATUS_TRANSITION_MAP[$order->getStatus()]];
    }

    /**
     * @return AbstractState
     */
    public function cancel(): AbstractState
    {
        return $this->states[OrderStatusType::CANCELED];
    }
}
