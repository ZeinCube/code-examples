<?php

namespace App\Service\AMQP\Handler;

use FEIP\VLP\Protocol\Message\AbstractMessage;
use FEIP\VLP\Protocol\Message\MessageType;

interface HandlerInterface
{
    /**
     * @see MessageType
     *
     * @return string
     */
    public static function getSupportedMessageType(): string;

    /**
     * @param AbstractMessage $message
     *
     * @return int
     */
    public function handle(AbstractMessage $message): int;
}
