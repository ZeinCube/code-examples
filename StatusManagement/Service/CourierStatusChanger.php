<?php

namespace App\Service;

use App\DBAL\Types\CourierStatusType;
use App\Entity\Courier;
use App\Exception\UnknownStatusException;
use App\Service\State\Courier\StateFactory;
use FEIP\VLP\Protocol\Message\RestaurantNotification\CourierStatusUpdateMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class CourierStatusChanger
{
    /**
     * @var StateFactory
     */
    private StateFactory $stateFactory;

    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $messageBus;

    /**
     * @param StateFactory        $stateFactory
     * @param MessageBusInterface $messageBus
     */
    public function __construct(StateFactory $stateFactory, MessageBusInterface $messageBus)
    {
        $this->stateFactory = $stateFactory;
        $this->messageBus   = $messageBus;
    }

    /**
     * @param Courier $courier
     * @param string  $status
     *
     * @throws UnknownStatusException
     *
     * @see CourierStatusType
     */
    public function setStatus(Courier $courier, string $status): void
    {
        $oldStatus = $courier->getStatus();
        $state     = $this->stateFactory->moveState($courier, $status);

        $state->processCourier($courier);

        if ($oldStatus !== $courier->getStatus()) {
            $message = $this->buildMessage($courier->getStatus(), $courier->getUser()->getPhone());
            $this->messageBus->dispatch($message);
        }
    }

    /**
     * @param string $status
     * @param string $courierPhone
     *
     * @return CourierStatusUpdateMessage
     */
    private function buildMessage(string $status, string $courierPhone): CourierStatusUpdateMessage
    {
        $message = new CourierStatusUpdateMessage();

        $message
            ->setStatus($status)
            ->setPhone($courierPhone)
        ;

        return $message;
    }
}
