<?php

namespace App\Service\AMQP\Handler;

use App\Exception\UnknownStatusException;
use App\Manager\OrderManager;
use App\Service\OrderStatusChanger;
use FEIP\VLP\Protocol\Message\AbstractMessage;
use FEIP\VLP\Protocol\Message\GeneralNotification\OrderCancelMessage;
use FEIP\VLP\Protocol\Message\MessageType;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use Psr\Log\LoggerInterface;

class OrderCancelHandler implements HandlerInterface
{
    /**
     * @var OrderStatusChanger
     */
    private OrderStatusChanger $orderStatusChanger;

    /**
     * @var OrderManager
     */
    private OrderManager $orderManager;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param OrderManager       $orderManager
     * @param OrderStatusChanger $orderStatusChanger
     * @param LoggerInterface    $logger
     */
    public function __construct(
        OrderManager $orderManager,
        OrderStatusChanger $orderStatusChanger,
        LoggerInterface $logger
    ) {
        $this->logger             = $logger;
        $this->orderManager       = $orderManager;
        $this->orderStatusChanger = $orderStatusChanger;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSupportedMessageType(): string
    {
        return MessageType::ORDER_CANCEL_TYPE;
    }

    /**
     * @param OrderCancelMessage $message
     *
     * @throws UnknownStatusException
     */
    public function handle(AbstractMessage $message): int
    {
        $order = $this->orderManager->find($message->getOrderId());

        if ($order === null) {
            $this->logger->critical('Could not cancel not existing order: ' . $message->getOrderId());

            return ConsumerInterface::MSG_REJECT;
        }

        $this->orderStatusChanger->cancel($order);

        $this->orderManager->delete($order);

        return ConsumerInterface::MSG_ACK;
    }
}
