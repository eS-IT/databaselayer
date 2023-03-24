<?php

/**
 * @package     Databaselayer
 * @since       27.09.2022 - 15:57
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2022
 * @license     EULA
 */

declare(strict_types=1);

namespace Esit\Databaselayer\Tests\Services\Helper;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Table;
use Esit\Databaselayer\Classes\Excaptions\InvalidArgumentException;
use Esit\Databaselayer\Classes\Services\Helper\ConnectionHelper;
use Esit\Databaselayer\Classes\Services\Helper\ExecutionHelper;
use Esit\Databaselayer\Classes\Services\Helper\SchemaHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SchemaHelperTest extends TestCase
{


    /**
     * @var ConnectionHelper&MockObject|MockObject
     */
    private $connectionHelper;


    /**
     * @var AbstractSchemaManager&MockObject|MockObject
     */
    private $schemaManager;


    /**
     * @var (ExecutionHelper&MockObject)|MockObject
     */
    private $execHelper;


    /**
     * @var SchemaHelper
     */
    private $helper;


    protected function setUp(): void
    {
        $this->connectionHelper = $this->getMockBuilder(ConnectionHelper::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->schemaManager    = $this->getMockBuilder(AbstractSchemaManager::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->execHelper       = $this->getMockBuilder(ExecutionHelper::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->connectionHelper->method('getSchemaManager')
                               ->willReturn($this->schemaManager);

        $this->helper           = new SchemaHelper($this->connectionHelper, $this->execHelper);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testGetDatabasesReturnArrayWithDatabasenames(): void
    {
        $dbs = ['test_db', 'example_db'];

        $this->schemaManager->expects(self::once())
                            ->method('listDatabases')
                            ->willReturn($dbs);

        $rtn = $this->helper->getDatabases();
        self::assertSame($dbs, $rtn);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testGetTablesReturnArrayWithTablenames(): void
    {
        $table  = $this->getMockBuilder(Table::class)
                       ->disableOriginalConstructor()
                       ->getMock();

        $table->expects(self::exactly(2))
              ->method('getName')
              ->willReturnOnConsecutiveCalls('tl_test', 'tl_example');

        $this->schemaManager->expects(self::once())
                            ->method('listTables')
                            ->willReturn([$table, $table]);

        $rtn = $this->helper->getTables();
        self::assertSame(['tl_test', 'tl_example'], $rtn);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testGetColumnsThrowExceptionIfTableIsEmpty(): void
    {
        $table = '';

        $this->schemaManager->expects(self::never())
                            ->method('listTableColumns');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('parameter could not be empty');
        $this->helper->getColumns($table);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testGetColumnsReturnArrayOfColumnnames(): void
    {
        $table = 'tl_test';

        $column = $this->getMockBuilder(Column::class)
                       ->disableOriginalConstructor()
                       ->getMock();

        $this->schemaManager->expects(self::once())
                            ->method('listTableColumns')
                            ->with($table)
                            ->willReturn([$column, $column, $column]);

        $column->expects(self::exactly(3))
               ->method('getName')
               ->willReturnOnConsecutiveCalls('id', 'tstamp', 'name');

        $rtn = $this->helper->getColumns($table);

        self::assertSame(['id', 'tstamp', 'name'], $rtn);
    }
}
