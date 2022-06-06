<?php

namespace FEIP\VLP\Protocol\Message;

interface MessageInterface
{
    /**
     * @return string
     */
    public static function getType(): string;

    /**
     * Indicates is message ready to be sent
     *
     * @return bool
     */
    public function isReady(): bool;
}
