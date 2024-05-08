<?php

/**
 * @since       25.01.2024 - 15:43
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2024
 * @license     EULA
 */

declare(strict_types=1);

namespace Esit\Databaselayer\Tests\Services\Helper;

use Esit\Databaselayer\Classes\Services\Helper\DateHelper;
use PHPUnit\Framework\TestCase;

class DateHelperTest extends TestCase
{

    public function testGetTimestampReturnTime(): void
    {
        $dateHelper = new DateHelper();
        $time       = \time();
        $rtn        = $dateHelper->getTimestamp();
        $this->assertTrue($rtn >= $time);
    }
}
