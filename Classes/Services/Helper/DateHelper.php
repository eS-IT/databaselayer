<?php

/**
 * @since       12.01.2024 - 14:57
 *
 * @author      Patrick Froch <info@easySolutionsIT.de>
 *
 * @see         http://easySolutionsIT.de
 *
 * @copyright   e@sy Solutions IT 2024
 * @license     EULA
 */

declare(strict_types=1);

namespace Esit\Databaselayer\Classes\Services\Helper;

class DateHelper
{
    /**
     * Gibt den aktuellen Timestamp zurück.
     *
     * @return int
     */
    public function getTimestamp(): int
    {
        return \time();
    }
}
