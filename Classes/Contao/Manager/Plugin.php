<?php

/**
 * @package   databaselayer
 * @since     22.09.2022 - 20:45
 * @author    Patrick Froch <info@easySolutionsIT.de>
 * @see       http://easySolutionsIT.de
 * @copyright e@sy Solutions IT 2022
 * @license   EULA
 */

declare(strict_types=1);

namespace Esit\Databaselayer\Classes\Contao\Manager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Config\ConfigInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Esit\Databaselayer\EsitDatabaselayerBundle;

class Plugin implements BundlePluginInterface
{


    /**
     * @param  ParserInterface $parser
     * @return array|ConfigInterface[]
     */
    public function getBundles(ParserInterface $parser)
    {
        return [BundleConfig::create(EsitDatabaselayerBundle::class)->setLoadAfter([ContaoCoreBundle::class])];
    }
}
