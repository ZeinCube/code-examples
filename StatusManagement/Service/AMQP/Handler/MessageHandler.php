<?php

namespace App\Service\AMQP\Handler;

use FEIP\VLP\Protocol\Message\AbstractMessage;
use FEIP\VLP\Protocol\Message\MessageBuilder;
use Psr\Log\LoggerInterface;
use Throwable;

class MessageHandler
{
    /**
     * @var HandlerInterface[]
     */
    private array $handlers = [];

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param LoggerInterface    $logger
     * @param HandlerInterface[] $handlers
     */
    public function __construct(LoggerInterface $logger, iterable $handlers)
    {
        foreach ($handlers as $handler) {
            $this->handlers[$handler::getSupportedMessageType()] = $handler;
        }

        $this->logger = $logger;
    }

    /**
     * @param AbstractMessage $message
     *
     * @throws Throwable
     *
     * @return int
     */
    public function handleMessage(AbstractMessage $message): int
    {
        try {
            return $this->handlers[$message::getType()]->handle($message);
        } catch (Throwable $e) {
            $this->logger->critical('Could not handle message: ' . MessageBuilder::toJson($message));

            throw $e;
        }
    }
}
