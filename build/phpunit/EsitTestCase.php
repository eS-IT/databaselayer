<?php declare(strict_types=1);
/**
 * @author      pfroch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2022
 * @license     EULA
 * @package     Databaselayer
 * @since       22.09.2022 - 20.50
 */
namespace Esit\Databaselayer;

use Contao\TestCase\ContaoTestCase;

/**
 * Class EsitTestCase
 */
class EsitTestCase extends ContaoTestCase
{


    /**
     * File with data.
     * @var string
     */
    protected $strDataProviderFile = '';


    /**
     * DataProvider
     * @var null
     */
    protected $varDataProvider = null;


    /**
     * EsitTestCase constructor.
     * @param null   $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->initializeContao();
    }


    /**
     * setup the environment
     */
    protected function setUp(): void
    {
    }


    /**
     * tear down the environment
     */
    protected function tearDown(): void
    {
    }


    /**
     * Initialisiert Contao
     * @param string $tlMode
     * @param string $tlScript
     * @throws \Exception
     */
    protected function initializeContao($tlMode = 'TEST', $tlScript = 'EsitTestCase'): void
    {
        $framework = $this->mockContaoFramework();
        $framework->method('initialize');

        if (!defined('TL_MODE')) {
            define('TL_MODE', $tlMode);
            define('TL_SCRIPT', $tlScript);
            $initializePath = CONTAO_ROOT . "/system/initialize.php";

            if (is_file($initializePath)) {
                require($initializePath);
                stream_wrapper_restore('phar');// reregister stream wrapper for phpunit.phar
            } else {
                throw new \Exception(CONTAO_ROOT . "/system/initialize.php not found!");
            }
        }
    }
}
