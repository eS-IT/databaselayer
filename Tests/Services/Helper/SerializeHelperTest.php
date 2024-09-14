<?php

/**
 * @since       14.09.2024 - 10:26
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2024
 * @license     EULA
 */

declare(strict_types=1);

namespace Esit\Tests\Service\Helper;

use Esit\Databaselayer\Classes\Services\Helper\SerializeHelper;
use PHPUnit\Framework\TestCase;

class SerializeHelperTest extends TestCase
{


    /**
     * @var SerializeHelper
     */
    private SerializeHelper $helper;


    protected function setUp(): void
    {
        $this->helper = new SerializeHelper();
    }


    public function testSerializeRetrunInteger(): void
    {
        $expected = 12;
        $this->assertSame($expected, $this->helper->serialize($expected));
    }


    public function testSerializeRetrunString(): void
    {
        $expected = "12";
        $this->assertSame($expected, $this->helper->serialize($expected));
    }


    public function testSerializeRetrunObject(): void
    {
        $expected = $this;
        $this->assertSame($expected, $this->helper->serialize($expected));
    }


    public function testSerializeRetrunSerializedArray(): void
    {
        $input      = [12, 'Test', 'String' => '12'];
        $expected   = \serialize($input);
        $this->assertSame($expected, $this->helper->serialize($input));
    }


    public function testUnserializeReturnInteger(): void
    {
        $expected = 12;
        $this->assertSame($expected, $this->helper->unserialize($expected));
    }


    public function testUnserializeReturnObject(): void
    {
        $expected = $this;
        $this->assertSame($expected, $this->helper->unserialize($expected));
    }


    public function testUnserializeReturnArray(): void
    {
        $expected = [12];
        $this->assertSame($expected, $this->helper->unserialize($expected));
    }


    public function testUnserializeReturnNormalString(): void
    {
        $expected = "Test eines normalen Strings";
        $this->assertSame($expected, $this->helper->unserialize($expected));
    }


    public function testUnserializeReturnArrayFromSerializedString(): void
    {
        $expected   = [12, 'Test', 'String' => '12'];
        $input      = \serialize($expected);
        $this->assertSame($expected, $this->helper->unserialize($input));
    }
}
