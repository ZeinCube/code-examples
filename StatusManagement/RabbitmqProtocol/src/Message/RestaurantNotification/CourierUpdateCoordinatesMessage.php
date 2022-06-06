<?php declare(strict_types=1);

namespace FEIP\VLP\Protocol\Message\RestaurantNotification;

use FEIP\VLP\Protocol\Message\AbstractMessage;
use FEIP\VLP\Protocol\Message\MessageType;

class CourierUpdateCoordinatesMessage extends AbstractMessage
{
    /**
     * @var string
     */
    private $courierPhoneNumber;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @param string $courierPhoneNumber
     *
     * @return self
     */
    public function setCourierPhoneNumber(string $courierPhoneNumber): self
    {
        $this->courierPhoneNumber = $courierPhoneNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getCourierPhoneNumber(): string
    {
        return $this->courierPhoneNumber;
    }

    /**
     * @param float $latitude
     *
     * @return self
     */
    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @param float $longitude
     *
     * @return self
     */
    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * {@inheritdoc}
     */
    public static function getType(): string
    {
        return MessageType::COURIER_UPDATE_COORDINATES;
    }
}
