<?php

namespace App\Service\Messenger;

use App\Service\AMQP\Producer\RestaurantNotificationProducer;
use FEIP\VLP\Protocol\Message\MessageBuilder;
use FEIP\VLP\Protocol\Message\MessageInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class MessageHandler implements MessageHandlerInterface
{
    private RestaurantNotificationProducer $producer;

    /**
     * {@inheritDoc}
     */
    public function __construct(RestaurantNotificationProducer $producer)
    {
        $this->producer = $producer;
    }

    /**
     * @param MessageInterface $message
     */
    public function __invoke(MessageInterface $message)
    {
        $this->producer->publish(MessageBuilder::toJson($message));
    }
}
