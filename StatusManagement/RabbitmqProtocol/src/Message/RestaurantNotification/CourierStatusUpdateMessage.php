<?php declare(strict_types=1);

namespace FEIP\VLP\Protocol\Message\RestaurantNotification;

use FEIP\VLP\Protocol\Message\AbstractMessage;
use FEIP\VLP\Protocol\Message\MessageType;

class CourierStatusUpdateMessage extends AbstractMessage
{
    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $status;

    /**
     * @param string $phone
     *
     * @return self
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $status
     *
     * @return self
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * {@inheritdoc }
     */
    public static function getType(): string
    {
        return MessageType::COURIER_STATUS_UPDATE_TYPE;
    }
}
