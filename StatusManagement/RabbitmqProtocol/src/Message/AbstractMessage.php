<?php declare(strict_types=1);

namespace FEIP\VLP\Protocol\Message;

use ReflectionObject;

abstract class AbstractMessage implements MessageInterface
{
    /**
     * {@inheritdoc}
     */
    public function isReady(): bool
    {
        $reflectionObject = new ReflectionObject($this);
        $properties = $reflectionObject->getProperties();

        foreach ($properties as $property) {
            $name = $property->getName();
            $this->{"get" . ucfirst($name)}();
        }

        return true;
    }
}
