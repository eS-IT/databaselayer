<?php

/**
 * @package   Databaselayer
 * @since     23.09.2022 - 09:23
 * @author    Patrick Froch <info@easySolutionsIT.de>
 * @see       http://easySolutionsIT.de
 * @copyright e@sy Solutions IT 2022
 * @license   EULA
 */

declare(strict_types=1);

namespace Esit\Databaselayer\Classes\Excaptions;

class InvalidArgumentException extends \InvalidArgumentException implements ExceptionInterface
{
}
