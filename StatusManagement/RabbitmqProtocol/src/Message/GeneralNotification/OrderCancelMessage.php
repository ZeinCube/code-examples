<?php declare(strict_types=1);

namespace FEIP\VLP\Protocol\Message\GeneralNotification;

use FEIP\VLP\Protocol\Message\AbstractMessage;
use FEIP\VLP\Protocol\Message\MessageType;

class OrderCancelMessage extends AbstractMessage
{
    /**
     * @var int
     */
    private $orderId;

    /**
     * @var string|null
     */
    private $cancelReason;

    /**
     * @param int $orderId
     *
     * @return self
     */
    public function setOrderId(int $orderId): self
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * @param string|null $cancelReason
     *
     * @return self
     */
    public function setCancelReason(?string $cancelReason): self
    {
        $this->cancelReason = $cancelReason;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCancelReason(): ?string
    {
        return $this->cancelReason;
    }

    /**
     * {@inheritdoc}
     */
    public static function getType(): string
    {
        return MessageType::ORDER_CANCEL_TYPE;
    }
}
