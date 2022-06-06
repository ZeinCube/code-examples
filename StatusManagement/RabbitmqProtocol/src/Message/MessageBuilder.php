<?php declare(strict_types=1);

namespace FEIP\VLP\Protocol\Message;

use DateTime;
use Exception;
use ReflectionObject;
use RuntimeException;

class MessageBuilder
{
    public const TYPE_INDEX = 'type';

    /**
     * @param string $json
     *
     * @return AbstractMessage
     * @throws Exception
     */
    public static function buildFromJson(string $json): AbstractMessage
    {
        $data = json_decode($json, true);
        $data = self::rebuildDateTime($data);

        $messageType = $data[self::TYPE_INDEX] ?? null;

        if ($messageType === null) {
            throw new RuntimeException("Provided data has no 'type' entry: " . $json);
        }

        $class = MessageType::getClassByType($messageType);
        unset($data[self::TYPE_INDEX]);

        $message = new $class();

        foreach ($data as $name => $value) {
            $name = self::snakeToCamel($name);
            $message->{"set".$name}($value);
        }

        return $message;
    }

    public static function toJson(MessageInterface $message): string
    {
        $message->isReady();
        $array = [];
        $reflectionObject = new ReflectionObject($message);
        $properties = $reflectionObject->getProperties();

        foreach ($properties as $property) {
            $name = $property->getName();
            $array[self::camelToSnake($name)] = $message->{"get" . ucfirst($name)}();
        }

        $array[self::TYPE_INDEX] = $message::getType();

        return json_encode($array);
    }


    /**
     * @param string $input
     *
     * @return string
     */
    private static function snakeToCamel(string $input): string
    {
        return ucfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $input))));
    }

    private static function camelToSnake(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    /**
     * @param array $array
     *
     * @return array
     *
     * @throws Exception
     */
    private static function rebuildDateTime(array $array): array
    {
        return array_map(static function ($item) {
            if(is_array($item) && isset($item['date'], $item['timezone_type'])) {
                return new DateTime($item['date']);
            }

            return $item;
        }, $array);
    }
}
