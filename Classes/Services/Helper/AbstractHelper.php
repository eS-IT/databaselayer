<?php

/**
 * @package   Databaselayer
 * @since     23.09.2022 - 09:20
 * @author    Patrick Froch <info@easySolutionsIT.de>
 * @see       http://easySolutionsIT.de
 * @copyright e@sy Solutions IT 2022
 * @license   EULA
 */

declare(strict_types=1);

namespace Esit\Databaselayer\Classes\Services\Helper;

abstract class AbstractHelper
{
    /**
     * @param ConnectionHelper $connectionHelper
     * @param ExecutionHelper  $execHelper
     */
    public function __construct(protected ConnectionHelper $connectionHelper, protected ExecutionHelper $execHelper)
    {
    }
}
