<?php

namespace App\Service\State\Courier;

use App\DBAL\Types\CourierStatusType;
use App\Entity\Courier;
use App\Exception\UnknownStatusException;
use App\Manager\CourierManager;
use App\Manager\OrderManager;

abstract class AbstractState
{
    public const ORDER_CANCELED = 'cancel';

    /**
     * @var CourierManager
     */
    protected CourierManager $courierManager;

    /**
     * @var OrderManager
     */
    protected OrderManager $orderManager;

    /**
     * @var array
     */
    protected array $transitions;

    /**
     * @param CourierManager $courierManager
     * @param OrderManager   $orderManager
     */
    public function __construct(CourierManager $courierManager, OrderManager $orderManager)
    {
        $this->courierManager = $courierManager;
        $this->orderManager   = $orderManager;
    }

    /**
     * @param Courier $courier
     */
    public function processCourier(Courier $courier): void
    {
        $courier->setStatus($this->getAlias());
        $this->courierManager->save($courier);
    }

    /**
     * @param Courier $courier
     * @param string  $nextStatus
     *
     * @throws UnknownStatusException
     *
     * @return string
     */
    public function getNextState(Courier $courier, string $nextStatus): string
    {
        if ($nextStatus === CourierStatusType::UNAVAILABLE) {
            return $this->unavailable($courier);
        }

        if ($nextStatus === CourierStatusType::FREE) {
            return $this->free($courier);
        }

        if ($nextStatus === self::ORDER_CANCELED) {
            return $this->cancelOrder($courier);
        }

        $transitions = $this->getAvailableTransitions();
        if (!array_key_exists($nextStatus, $transitions)) {
            throw new UnknownStatusException(static::class, $nextStatus);
        }

        return $transitions[$nextStatus];
    }

    /**
     * @return string
     */
    abstract public function getAlias(): string;

    /**
     * @param Courier $courier
     *
     * @return string
     */
    abstract protected function unavailable(Courier $courier): string;

    /**
     * @param Courier $courier
     *
     * @return string
     */
    abstract protected function free(Courier $courier): string;

    /**
     * @param Courier $courier
     *
     * @return string
     */
    abstract protected function cancelOrder(Courier $courier): string;

    /**
     * @return array
     */
    abstract protected function getAvailableTransitions(): array;
}
