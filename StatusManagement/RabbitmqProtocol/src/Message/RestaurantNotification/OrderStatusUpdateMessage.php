<?php declare(strict_types=1);

namespace FEIP\VLP\Protocol\Message\RestaurantNotification;

use FEIP\VLP\Protocol\Message\AbstractMessage;
use FEIP\VLP\Protocol\Message\MessageType;

class OrderStatusUpdateMessage extends AbstractMessage
{
    /**
     * @var string
     */
    private $orderNumber;

    /**
     * @var string
     */
    private $orderStatus;

    /**
     * @param string $orderNumber
     *
     * @return self
     */
    public function setOrderNumber(string $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    /**
     * @param string $orderStatus
     *
     * @return self
     */
    public function setOrderStatus(string $orderStatus): self
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderStatus(): string
    {
        return $this->orderStatus;
    }

    /**
     * {@inheritdoc}
     */
    public static function getType(): string
    {
        return MessageType::ORDER_STATUS_UPDATE_TYPE;
    }
}
