<?php

/**
 * @package     databaselayer
 * @since       22.09.2022 - 20:45
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2022
 * @license     EULA
 */

declare(strict_types = 1);

namespace Esit\Databaselayer\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class EsitDatabaselayerExtension extends Extension
{


    /**
     * Lädt die Konfigurationen
     * @param array            $mergedConfig
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $mergedConfig, ContainerBuilder $container): void
    {
        $folder = __DIR__ . '/../Resources/config';
        $loader = new YamlFileLoader($container, new FileLocator($folder));

        if (\is_file("$folder/services.yml")) {
            $loader->load('services.yml');
        }
    }
}
