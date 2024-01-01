<?php

namespace Core\API\Types;

/**
 * Abstract setter for types
 */
abstract class Type
{
    public function set(string $propertyName, mixed $value = null): mixed
    {
        // @todo проверить, будет ли работать если тут property_exists
        if ($value !== null && property_exists($this, $propertyName)) {
            $this->$propertyName = $value;
        }

        return $this->$propertyName ?? null;
    }

    public function get(string $propertyName): mixed
    {
        return $this->$propertyName ?? null;
    }
}
