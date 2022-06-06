<?php

namespace App\Service\AMQP\Handler;

use App\DBAL\Types\OrderStatusType;
use App\Entity\Order;
use App\Manager\CourierManager;
use App\Manager\OrderManager;
use Exception;
use FEIP\VLP\Protocol\Message\AbstractMessage;
use FEIP\VLP\Protocol\Message\MessageType;
use FEIP\VLP\Protocol\Message\MobileNotification\OrderSetCourierMessage;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;

class OrderSetCourierHandler implements HandlerInterface
{
    /**
     * @var OrderManager
     */
    private OrderManager $orderManager;

    /**
     * @var CourierManager
     */
    private CourierManager $courierManager;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param OrderManager    $orderManager
     * @param CourierManager  $courierManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        OrderManager $orderManager,
        CourierManager $courierManager,
        LoggerInterface $logger
    ) {
        $this->orderManager   = $orderManager;
        $this->courierManager = $courierManager;
        $this->logger         = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSupportedMessageType(): string
    {
        return MessageType::ORDER_SET_COURIER_TYPE;
    }

    /**
     * @param OrderSetCourierMessage $message
     */
    public function handle(AbstractMessage $message): int
    {
        $order = new Order();
        $order
            ->setStatus(OrderStatusType::ACCEPTED)
            ->setClientPhone($message->getClientPhone())
            ->setDeliveryAddress($message->getDeliveryAddress())
            ->setDeliveryScheduledTime($message->getDeliveryTime())
            ->setNumber($message->getOrderNumber())
            ->setPaymentType($message->getPaymentType())
            ->setPointOfIssue($message->getPointOfIssue())
            ->setTotalPrice($message->getTotalPrice())
            ->setComment($message->getComment())
        ;

        try {
            $courierPhone = $message->getCourierPhone();
            $courier      = $this->courierManager->findByPhone($courierPhone);

            if ($courier === null) {
                throw new RuntimeException();
            }

            $order->setCourier($courier);
            $this->orderManager->save($order);
        } catch (Exception $e) {
            $this->logger->critical(
                sprintf('Could not set order to courier. Order id: %s Courier phone: %s',
                    $message->getOrderNumber(),
                    $courierPhone)
            );

            return ConsumerInterface::MSG_REJECT;
        }

        return ConsumerInterface::MSG_ACK;
    }
}
