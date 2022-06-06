<?php declare(strict_types=1);

namespace FEIP\VLP\Protocol\Message;

use FEIP\VLP\Protocol\Message\GeneralNotification\OrderCancelMessage;
use FEIP\VLP\Protocol\Message\MobileNotification\OrderSetCourierMessage;
use FEIP\VLP\Protocol\Message\RestaurantNotification\CourierStatusUpdateMessage;
use FEIP\VLP\Protocol\Message\RestaurantNotification\CourierUpdateCoordinatesMessage;
use FEIP\VLP\Protocol\Message\RestaurantNotification\OrderStatusUpdateMessage;
use FEIP\VLP\Protocol\Message\RestaurantNotification\SosNotification;
use RuntimeException;

class MessageType
{
    public const ORDER_SET_COURIER_TYPE     = 'order_set_courier_type';
    public const ORDER_CANCEL_TYPE          = 'order_cancel_type';
    public const ORDER_STATUS_UPDATE_TYPE   = 'order_status_update_type';
    public const COURIER_UPDATE_COORDINATES = 'courier_coordinates_update_type';
    public const COURIER_STATUS_UPDATE_TYPE = 'courier_status_update_type';
    public const SOS_COURIERS_TYPE          = 'courier_sos_type';

    public const TYPE_MAPPING = [
        self::ORDER_SET_COURIER_TYPE     => OrderSetCourierMessage::class,
        self::ORDER_CANCEL_TYPE          => OrderCancelMessage::class,
        self::ORDER_STATUS_UPDATE_TYPE   => OrderStatusUpdateMessage::class,
        self::COURIER_UPDATE_COORDINATES => CourierUpdateCoordinatesMessage::class,
        self::COURIER_STATUS_UPDATE_TYPE => CourierStatusUpdateMessage::class,
        self::SOS_COURIERS_TYPE          => SosNotification::class,
    ];

    /**
     * @param string $type
     *
     * @return string
     */
    public static function getClassByType(string $type): string
    {
        if (!isset(self::TYPE_MAPPING[$type])) {
            throw new RuntimeException('No such type: ' . $type);
        }

        return self::TYPE_MAPPING[$type];
    }
}
