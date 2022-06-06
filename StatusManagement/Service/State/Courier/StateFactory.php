<?php

namespace App\Service\State\Courier;

use App\Entity\Courier;
use App\Exception\UnknownStatusException;

class StateFactory
{
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
     * @param Courier $courier
     * @param string  $state
     *
     * @throws UnknownStatusException
     *
     * @return AbstractState
     */
    public function moveState(Courier $courier, string $state): AbstractState
    {
        $currentState = $this->states[$courier->getStatus()];
        $this->isValidStatus($state);

        return $this->states[$currentState->getNextState($courier, $state)];
    }

    /**
     * @param string $state
     *
     * @throws UnknownStatusException
     */
    private function isValidStatus(string $state): void
    {
        if ($state !== AbstractState::ORDER_CANCELED && !array_key_exists($state, $this->states)) {
            throw new UnknownStatusException(static::class, $state);
        }
    }
}
