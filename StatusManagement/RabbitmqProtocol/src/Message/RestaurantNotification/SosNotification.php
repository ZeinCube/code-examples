<?php declare(strict_types=1);

namespace FEIP\VLP\Protocol\Message\RestaurantNotification;

use FEIP\VLP\Protocol\Message\AbstractMessage;
use FEIP\VLP\Protocol\Message\MessageType;

class SosNotification extends AbstractMessage
{
    /**
     * @var string
     */
    private $phone;

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
     * {@inheritdoc}
     */
    public static function getType(): string
    {
        return MessageType::SOS_COURIERS_TYPE;
    }
}
