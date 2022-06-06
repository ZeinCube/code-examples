<?php

namespace App\Service;

use App\Entity\Order;
use App\Exception\UnknownStatusException;
use App\Service\State\Order\CancelState;
use App\Service\State\Order\StateFactory;
use FEIP\VLP\Protocol\Message\GeneralNotification\OrderCancelMessage;
use FEIP\VLP\Protocol\Message\RestaurantNotification\OrderStatusUpdateMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class OrderStatusChanger
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
     * @param Order $order
     *
     * @throws UnknownStatusException
     */
    public function moveStatus(Order $order): void
    {
        $nextState = $this->stateFactory->getNextState($order);

        $nextState->processOrder($order);
        $message = $this->buildMessage($order->getNumber(), $order->getStatus());

        $this->messageBus->dispatch($message);
    }

    /**
     * @param Order $order
     *
     * @throws UnknownStatusException
     */
    public function cancel(Order $order): void
    {
        /** @var CancelState $cancelState */
        $cancelState = $this->stateFactory->cancel();
        $cancelState->processOrder($order);

        $message = $this->buildCancelMessage($order->getNumber(), $order->getCancelReason());

        $this->messageBus->dispatch($message);
    }

    /**
     * @param string      $orderNumber
     * @param string|null $cancelReason
     *
     * @return OrderCancelMessage
     */
    private function buildCancelMessage(string $orderNumber, ?string $cancelReason): OrderCancelMessage
    {
        $message = new OrderCancelMessage();

        $message
            ->setOrderId($orderNumber)
            ->setCancelReason($cancelReason)
        ;

        return $message;
    }

    /**
     * @param string $number
     * @param string $status
     *
     * @return OrderStatusUpdateMessage
     */
    private function buildMessage(string $number, string $status): OrderStatusUpdateMessage
    {
        $message = new OrderStatusUpdateMessage();

        $message
            ->setOrderNumber($number)
            ->setOrderStatus($status)
        ;

        return $message;
    }
}
