<?php

namespace App\Service\AMQP\Consumer;

use App\Service\AMQP\Handler\MessageHandler;
use FEIP\VLP\Protocol\Message\MessageBuilder;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Throwable;

class RestaurantNotificationConsumer implements ConsumerInterface
{
    private MessageHandler $handler;

    /**
     * {@inheritDoc}
     */
    public function __construct(MessageHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Throwable
     */
    public function execute(AMQPMessage $msg)
    {
        $message = MessageBuilder::buildFromJson($msg->body);

        return $this->handler->handleMessage($message);
    }
}
