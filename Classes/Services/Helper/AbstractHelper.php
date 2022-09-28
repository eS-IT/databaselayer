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
     * @var ConnectionHelper
     */
    protected ConnectionHelper $connectionHelper;


    /**
     * @param ConnectionHelper $conHelper
     */
    public function __construct(ConnectionHelper $conHelper)
    {
        $this->connectionHelper = $conHelper;
    }
}
