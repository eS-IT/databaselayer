<?php

/**
 * @package     Databaselayer
 * @since       27.09.2022 - 14:32
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2022
 * @license     EULA
 */

declare(strict_types=1);

namespace Esit\Databaselayer\Tests\Services\Helper;

use Doctrine\DBAL\Exception;
use Esit\Databaselayer\Classes\Excaptions\InvalidArgumentException;
use Esit\Databaselayer\Classes\Services\Helper\DataHelper;
use Esit\Databaselayer\Classes\Services\Helper\SchemaHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DataHelperTest extends TestCase
{


    /**
     * @var DataHelper&MockObject|MockObject
     */
    private $schemaHelper;


    /**
     * @var DataHelper
     */
    private DataHelper $helper;


    protected function setUp(): void
    {
        $this->schemaHelper = $this->getMockBuilder(SchemaHelper::class)
                                   ->disableOriginalConstructor()
                                   ->getMock();

        $this->helper       = new DataHelper($this->schemaHelper);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testFilterDataForDatabaseThrowExceptionIfRowIsEmpty(): void
    {
        $row        = [];
        $table      = 'tl_test';
        $dbFields   = [];

        $this->schemaHelper->expects(self::once())
                           ->method('getColumns')
                           ->with($table)
                           ->willReturn($dbFields);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('parameter could not be empty');
        $this->helper->filterDataForDatabase($row, $table);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testFilterDataForDatabaseThrowExceptionIfTableIsEmpty(): void
    {
        $row        = ['id' => 12, 'name' => 'Test'];
        $table      = '';
        $dbFields   = [];

        $this->schemaHelper->expects(self::once())
                           ->method('getColumns')
                           ->with($table)
                           ->willReturn($dbFields);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('parameter could not be empty');
        $this->helper->filterDataForDatabase($row, $table);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testFilterDataForDatabaseRemoveFields(): void
    {
        $row        = ['id' => 12, 'name' => 'Test', 'kann' => 'Weg!'];
        $table      = 'tl_test';
        $dbFields   = ['id', 'name'];
        $expected   = ['id' => 12, 'name' => 'Test'];

        $this->schemaHelper->expects(self::once())
                           ->method('getColumns')
                           ->with($table)
                           ->willReturn($dbFields);

        $rtn = $this->helper->filterDataForDatabase($row, $table);
        self::assertSame($expected, $rtn);
    }


    public function testFilterFieldsForInsertThrowExceptionIfRowIsEmpty(): void
    {
        $row        = [];
        $table      = 'tl_test';
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('parameter could not be empty');
        $this->helper->filterFieldsForInsert($row, $table);
    }


    public function testFilterFieldsForInsertThrowExceptionIfTableIsEmpty(): void
    {
        $row        = ['id' => 12, 'name' => 'Test'];
        $table      = '';
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('parameter could not be empty');
        $this->helper->filterFieldsForInsert($row, $table);
    }


    public function testFilterFieldsForInsertReturnArrayOfFields(): void
    {
        $row        = ['id' => 12, 'name' => 'Test'];
        $table      = 'tl_test';
        $expected   = ['id' => ':id', 'name' => ':name'];
        $rtn        = $this->helper->filterFieldsForInsert($row, $table);
        self::assertSame($expected, $rtn);
    }
}
