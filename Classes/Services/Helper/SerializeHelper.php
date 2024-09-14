<?php

/**
 * @since       14.09.2024 - 10:22
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2024
 * @license     EULA
 */

declare(strict_types=1);

namespace Esit\Databaselayer\Classes\Services\Helper;

class SerializeHelper
{
    /**
     * Wandelt ein PHP Array in ein serialisiertes Array um.
     * Andere Datentypen werden unverändert zurückgegeben.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function serialize(mixed $value): mixed
    {
        return true === \is_array($value) ? \serialize($value) : $value;
    }



    /**
     * WAndlet ein serialisiertes Array in ein PHP Array um.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function unserialize(mixed $value): mixed
    {
        if (true === \is_string($value)) {
            $convertedValue = @\unserialize($value, ['allowed_classes' => false]);

            if (true === \is_array($convertedValue)) {
                return $convertedValue;
            }
        }

        return $value;
    }
}
