<?php

/**
 * @package     Databaselayer
 * @since       24.09.2022 - 17:29
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2022
 * @license     EULA
 */

declare(strict_types=1);

namespace Esit\Databaselayer\Tests\Services\Helper;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Column;
use Esit\Databaselayer\Classes\Services\Helper\ConnectionHelper;
use Esit\Databaselayer\Classes\Services\Helper\DatabaseHelper;
use Esit\Databaselayer\Classes\Services\Helper\DateHelper;
use Esit\Databaselayer\Classes\Services\Helper\QueryHelper;
use Esit\Databaselayer\Classes\Services\Helper\StatementHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DatabaseHelperTest extends TestCase
{


    /**
     * @var (ConnectionHelper&MockObject)|MockObject
     */
    private $connectionHelper;


    /**
     * @var (QueryHelper&MockObject)|MockObject
     */
    private $queryHelper;


    /**
     * @var (StatementHelper&MockObject)|MockObject
     */
    private $statementHelper;


    /**
     * @var (AbstractSchemaManager&MockObject)|MockObject
     */
    private $schemaManager;


    /**
     * @var (DateHelper&MockObject)|MockObject
     */
    private $dateHelper;


    /**
     * @var DatabaseHelper
     */
    private DatabaseHelper $helper;


    protected function setUp(): void
    {
        $this->connectionHelper = $this->getMockBuilder(ConnectionHelper::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->queryHelper      = $this->getMockBuilder(QueryHelper::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->statementHelper  = $this->getMockBuilder(StatementHelper::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->dateHelper       = $this->getMockBuilder(DateHelper::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->schemaManager    = $this->getMockBuilder(AbstractSchemaManager::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->helper           = new DatabaseHelper(
            $this->connectionHelper,
            $this->queryHelper,
            $this->statementHelper,
            $this->dateHelper
        );
    }


    public function testGetConnectionWillCallConnectionHelper(): void
    {
        $query = $this->getMockBuilder(Connection::class)
                      ->disableOriginalConstructor()
                      ->getMock();

        $this->connectionHelper->expects(self::once())
                               ->method('getConnection')
                               ->willReturn($query);

        self::assertSame($query, $this->helper->getConnection());
    }


    public function testGetQueryBuilderWillCallConnectionHelper(): void
    {
        $connection = $this->getMockBuilder(QueryBuilder::class)
                           ->disableOriginalConstructor()
                           ->getMock();

        $this->connectionHelper->expects(self::once())
                               ->method('getQueryBuilder')
                               ->willReturn($connection);

        self::assertSame($connection, $this->helper->getQueryBuilder());
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testGetSchemaManagerWillCallConnectionHelper(): void
    {
        $schema = $this->getMockBuilder(AbstractSchemaManager::class)
                       ->disableOriginalConstructor()
                       ->getMock();

        $this->connectionHelper->expects(self::once())
                               ->method('getSchemaManager')
                               ->willReturn($schema);

        self::assertSame($schema, $this->helper->getSchemaManager());
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testLoadByValueWillCallQueryHelper(): void
    {
        $value  = 12;
        $field  = 'id';
        $table  = 'tl_test';
        $offset = 34;
        $limit  = 56;
        $data   = ['id' => 12, 'name' => 'test'];

        $this->queryHelper->expects(self::once())
                          ->method('loadByValue')
                          ->with($value, $field, $table, $offset, $limit)
                          ->willReturn($data);

        self::assertSame($data, $this->helper->loadByValue($value, $field, $table, $offset, $limit));
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testLoadByListWillCallQueryHelper(): void
    {
        $values = [12, 13];
        $field  = 'id';
        $table  = 'tl_test';
        $order  = 'DESC';
        $offset = 34;
        $limit  = 56;
        $data   = [['id' => 12, 'name' => 'test'], ['id' => 13, 'name' => 'testTwo']];

        $this->queryHelper->expects(self::once())
                          ->method('loadByList')
                          ->with($values, $field, $table, $order, $offset, $limit)
                          ->willReturn($data);

        self::assertSame($data, $this->helper->loadByList($values, $field, $table, $order, $offset, $limit));
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testLoadAll(): void
    {
        $table      = 'tl_test';
        $orderField = 'id';
        $order      = 'DESC';
        $offset     = 34;
        $limit      = 56;
        $data       = [['id' => 12, 'name' => 'test'], ['id' => 13, 'name' => 'testTwo']];

        $this->queryHelper->expects(self::once())
                          ->method('loadAll')
                          ->with($table, $orderField, $order, $offset, $limit)
                          ->willReturn($data);

        self::assertSame($data, $this->helper->loadAll($table, $orderField, $order, $offset, $limit));
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testInsertWillCallStatementHelper(): void
    {
        $data   = ['name' => 'test'];
        $table  = 'tl_test';
        $id     = 12;

        $this->statementHelper->expects(self::once())
                              ->method('insert')
                              ->with($data, $table)
                              ->willReturn($id);

        self::assertSame($id, $this->helper->insert($data, $table));
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testUpdateWillCallStatementHelper(): void
    {
        $data   = ['name' => 'test'];
        $table  = 'tl_test';
        $id     = 12;

        $this->statementHelper->expects(self::once())
                              ->method('update')
                              ->with($data, $id, $table);

        $this->helper->update($data, $id, $table);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testDeleteWillCallStatementHelper(): void
    {
        $value  = '12';
        $field  = 'id';
        $table  = 'tl_test';

        $this->statementHelper->expects(self::once())
                              ->method('delete')
                              ->with($value, $field, $table);

        $this->helper->delete($value, $field, $table);
    }


    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function testGetTableFieldsReturnEmpytArrayIfNoFieldsFound(): void
    {
        $column         = $this->getMockBuilder(Column::class)
                               ->disableOriginalConstructor()
                               ->getMock();

        $table          = 'tl_test';

        $this->connectionHelper->expects(self::once())
                               ->method('getSchemaManager')
                               ->willReturn($this->schemaManager);

        $this->schemaManager->expects(self::once())
                            ->method('listTableColumns')
                            ->with($table)
                            ->willReturn(null);

        $column->expects(self::never())
               ->method('getName');

        $this->assertEmpty($this->helper->getTableFields($table));
    }


    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function testGetTableFieldsReturnFieldsIfFieldsFound(): void
    {
        $column         = $this->getMockBuilder(Column::class)
                               ->disableOriginalConstructor()
                               ->getMock();

        $tableFields    = [$column, $column];
        $table          = 'tl_test';

        $this->connectionHelper->expects(self::once())
                               ->method('getSchemaManager')
                               ->willReturn($this->schemaManager);

        $this->schemaManager->expects(self::once())
                            ->method('listTableColumns')
                            ->with($table)
                            ->willReturn($tableFields);

        $column->expects(self::exactly(\count($tableFields)))
               ->method('getName')
               ->willReturn('test');

        $this->assertSame(['test', 'test'], $this->helper->getTableFields($table));
    }


    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function testGetTableFieldsDoNothingIfDataIsEmtpy(): void
    {
        $table  = 'tl_test';
        $data   = [];

        $this->connectionHelper->expects(self::never())
                               ->method('getSchemaManager');

        $this->schemaManager->expects(self::never())
                            ->method('listTableColumns');

        $this->assertEmpty($this->helper->filterDataForDb($table, $data));
    }


    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function testGetTableFieldsReturnFieldsIfDataIsNotEmtpy(): void
    {
        $column         = $this->getMockBuilder(Column::class)
                               ->disableOriginalConstructor()
                               ->getMock();

        $tableFields    = [$column];
        $table          = 'tl_test';
        $data           = ['test' => 'myTest', 'notIn'=>'Table'];

        $this->connectionHelper->expects(self::once())
                               ->method('getSchemaManager')
                               ->willReturn($this->schemaManager);

        $this->schemaManager->expects(self::once())
                            ->method('listTableColumns')
                            ->with($table)
                            ->willReturn($tableFields);

        $column->expects(self::exactly(\count($tableFields)))
               ->method('getName')
               ->willReturn('test');

        $this->assertSame(['test' => 'myTest'], $this->helper->filterDataForDb($table, $data));
    }


    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function testSaveCallInsertIfNoIdFound(): void
    {
        $column         = $this->getMockBuilder(Column::class)
                               ->disableOriginalConstructor()
                               ->getMock();

        $tableFields    = [$column, $column];
        $table          = 'tl_test';
        $data           = ['test' => 'myTest', 'notIn'=>'Table'];
        $id             = 12;
        $time           = \time();
        $expected       = ['test' => 'myTest', 'tstamp' => $time];

        $this->dateHelper->expects(self::once())
                         ->method('getTimestamp')
                         ->willReturn($time);

        $this->connectionHelper->expects(self::once())
                               ->method('getSchemaManager')
                               ->willReturn($this->schemaManager);

        $this->schemaManager->expects(self::once())
                            ->method('listTableColumns')
                            ->with($table)
                            ->willReturn($tableFields);

        $this->statementHelper->expects(self::once())
                              ->method('insert')
                              ->with($expected, $table)
                              ->willReturn($id);

        $column->expects(self::exactly(\count($tableFields)))
               ->method('getName')
               ->willReturnOnConsecutiveCalls('test', 'tstamp');

        $this->assertSame($id, $this->helper->save($table, $data));
    }


    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function testSaveCallUpdateIfNoIdFound(): void
    {
        $column         = $this->getMockBuilder(Column::class)
                               ->disableOriginalConstructor()
                               ->getMock();

        $tableFields    = [$column, $column, $column];
        $table          = 'tl_test';
        $id             = 12;
        $data           = ['id' => $id, 'test' => 'myTest', 'notIn'=>'Table'];
        $time           = \time();
        $expected       = ['test' => 'myTest', 'tstamp' => $time];

        $this->dateHelper->expects(self::once())
                         ->method('getTimestamp')
                         ->willReturn($time);

        $this->connectionHelper->expects(self::once())
                               ->method('getSchemaManager')
                               ->willReturn($this->schemaManager);

        $this->schemaManager->expects(self::once())
                            ->method('listTableColumns')
                            ->with($table)
                            ->willReturn($tableFields);

        $this->statementHelper->expects(self::once())
                              ->method('update')
                              ->with($expected, $id, $table);

        $column->expects(self::exactly(\count($tableFields)))
               ->method('getName')
               ->willReturnOnConsecutiveCalls('test', 'tstamp', 'id');

        $this->assertSame($id, $this->helper->save($table, $data));
    }
}

