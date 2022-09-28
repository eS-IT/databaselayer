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
use Esit\Databaselayer\Classes\Services\Helper\ConnectionHelper;
use Esit\Databaselayer\Classes\Services\Helper\DatabaseHelper;
use Esit\Databaselayer\Classes\Services\Helper\QueryHelper;
use Esit\Databaselayer\Classes\Services\Helper\StatementHelper;
use PHPUnit\Framework\TestCase;

class DatabaseHelperTest extends TestCase
{


    /**
     * @var ConnectionHelper
     */
    private $connectionHelper;


    /**
     * @var QueryHelper
     */
    private $queryHelper;


    /**
     * @var StatementHelper
     */
    private $statementHelper;


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

        $this->helper           = new DatabaseHelper(
            $this->connectionHelper,
            $this->queryHelper,
            $this->statementHelper
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
        $data   = ['id' => 12, 'name' => 'test'];

        $this->queryHelper->expects(self::once())
                          ->method('loadByValue')
                          ->with($value, $field, $table)
                          ->willReturn($data);

        self::assertSame($data, $this->helper->loadByValue($value, $field, $table));
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
        $data   = [['id' => 12, 'name' => 'test'], ['id' => 13, 'name' => 'testTwo']];

        $this->queryHelper->expects(self::once())
                          ->method('loadByList')
                          ->with($values, $field, $table)
                          ->willReturn($data);

        self::assertSame($data, $this->helper->loadByList($values, $field, $table));
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
}
