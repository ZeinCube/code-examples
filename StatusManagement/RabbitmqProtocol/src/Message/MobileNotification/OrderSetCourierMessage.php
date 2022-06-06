<?php declare(strict_types=1);

namespace FEIP\VLP\Protocol\Message\MobileNotification;

use DateTime;
use FEIP\VLP\Protocol\Message\AbstractMessage;
use FEIP\VLP\Protocol\Message\MessageType;

class OrderSetCourierMessage extends AbstractMessage
{
    /**
     * @var int
     */
    private $orderNumber;

    /**
     * @var string
     */
    private $pointOfIssue;

    /**
     * @var string
     */
    private $deliveryAddress;

    /**
     * @var DateTime
     */
    private $deliveryTime;

    /**
     * @var float
     */
    private $totalPrice;

    /**
     * @var string
     */
    private $clientPhone;

    /**
     * @var string
     */
    private $courierPhone;

    /**
     * @var string
     */
    private $paymentType;

    /**
     * @var string|null
     */
    private $comment;

    /**
     * @param int $orderNumber
     *
     * @return self
     */
    public function setOrderNumber(int $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * @return int
     */
    public function getOrderNumber(): int
    {
        return $this->orderNumber;
    }

    /**
     * @param string $pointOfIssue
     *
     * @return self
     */
    public function setPointOfIssue(string $pointOfIssue): self
    {
        $this->pointOfIssue = $pointOfIssue;

        return $this;
    }

    /**
     * @return string
     */
    public function getPointOfIssue(): string
    {
        return $this->pointOfIssue;
    }

    /**
     * @param string $deliveryAddress
     *
     * @return self
     */
    public function setDeliveryAddress(string $deliveryAddress): self
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    /**
     * @return string
     */
    public function getDeliveryAddress(): string
    {
        return $this->deliveryAddress;
    }

    /**
     * @param DateTime $deliveryTime
     *
     * @return self
     */
    public function setDeliveryTime(DateTime $deliveryTime): self
    {
        $this->deliveryTime = $deliveryTime;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDeliveryTime(): DateTime
    {
        return $this->deliveryTime;
    }

    /**
     * @param float $totalPrice
     *
     * @return self
     */
    public function setTotalPrice(float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    /**
     * @param string $clientPhone
     *
     * @return self
     */
    public function setClientPhone(string $clientPhone): self
    {
        $this->clientPhone = $clientPhone;

        return $this;
    }

    /**
     * @return string
     */
    public function getClientPhone(): string
    {
        return $this->clientPhone;
    }

    /**
     * @param string $courierPhone
     *
     * @return self
     */
    public function setCourierPhone(string $courierPhone): self
    {
        $this->courierPhone = $courierPhone;

        return $this;
    }

    /**
     * @return string
     */
    public function getCourierPhone(): string
    {
        return $this->courierPhone;
    }

    /**
     * @param string $paymentType
     *
     * @return self
     */
    public function setPaymentType(string $paymentType): self
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentType(): string
    {
        return $this->paymentType;
    }

    /**
     * @param string|null $comment
     *
     * @return self
     */
    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * {@inheritdoc}
     */
    public static function getType(): string
    {
        return MessageType::ORDER_SET_COURIER_TYPE;
    }
}
