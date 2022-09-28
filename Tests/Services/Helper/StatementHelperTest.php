<?php

/**
 * @package     Databaselayer
 * @since       27.09.2022 - 16:17
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
use Esit\Databaselayer\Classes\Excaptions\InvalidArgumentException;
use Esit\Databaselayer\Classes\Excaptions\NoDataFoundException;
use Esit\Databaselayer\Classes\Services\Helper\ConnectionHelper;
use Esit\Databaselayer\Classes\Services\Helper\DataHelper;
use Esit\Databaselayer\Classes\Services\Helper\StatementHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class StatementHelperTest extends TestCase
{


    /**
     * @var ConnectionHelper&MockObject|MockObject
     */
    private $connectionHelper;


    /**
     * @var QueryBuilder&MockObject|MockObject
     */
    private $queryBuilder;


    /**
     * @var DataHelper&MockObject|MockObject
     */
    private $dataHelper;


    /**
     * @var StatementHelper
     */
    private StatementHelper $helper;


    protected function setUp(): void
    {
        $this->connectionHelper = $this->getMockBuilder(ConnectionHelper::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->queryBuilder     = $this->getMockBuilder(QueryBuilder::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->dataHelper       = $this->getMockBuilder(DataHelper::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->connectionHelper->method('getQueryBuilder')
                               ->willReturn($this->queryBuilder);

        $this->helper           = new StatementHelper($this->connectionHelper, $this->dataHelper);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testUpdateThrowExceptionIfDataIsEmpty(): void
    {
        $data   = [];
        $id     = 12;
        $table  = 'tl_test';

        $this->dataHelper->expects(self::never())
                         ->method('filterDataForDatabase');

        $this->queryBuilder->expects(self::never())
                           ->method('update');

        $this->queryBuilder->expects(self::never())
                           ->method('set');

        $this->queryBuilder->expects(self::never())
                           ->method('where');

        $this->queryBuilder->expects(self::never())
                           ->method('setParameter');

        $this->queryBuilder->expects(self::never())
                           ->method('executeStatement');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('parameter could not be empty');

        $this->helper->update($data, $id, $table);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testUpdateThrowExceptionIfIdIsEmpty(): void
    {
        $data   = ['id' => 12, 'name' => 'Test'];
        $id     = 0;
        $table  = 'tl_test';

        $this->dataHelper->expects(self::never())
                         ->method('filterDataForDatabase');

        $this->queryBuilder->expects(self::never())
                           ->method('update');

        $this->queryBuilder->expects(self::never())
                           ->method('set');

        $this->queryBuilder->expects(self::never())
                           ->method('where');

        $this->queryBuilder->expects(self::never())
                           ->method('setParameter');

        $this->queryBuilder->expects(self::never())
                           ->method('executeStatement');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('parameter could not be empty');

        $this->helper->update($data, $id, $table);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testUpdateThrowExceptionIfTableIsEmpty(): void
    {
        $data   = ['id' => 12, 'name' => 'Test'];
        $id     = 12;
        $table  = '';
        $this->dataHelper->expects(self::never())
                         ->method('filterDataForDatabase');

        $this->queryBuilder->expects(self::never())
                           ->method('update');

        $this->queryBuilder->expects(self::never())
                           ->method('set');

        $this->queryBuilder->expects(self::never())
                           ->method('where');

        $this->queryBuilder->expects(self::never())
                           ->method('setParameter');

        $this->queryBuilder->expects(self::never())
                           ->method('executeStatement');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('parameter could not be empty');

        $this->helper->update($data, $id, $table);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testUpdateCallQuery(): void
    {
        $data   = ['id' => 12, 'name' => 'Test'];
        $id     = 12;
        $table  = 'tl_test';

        $this->dataHelper->expects(self::once())
                         ->method('filterDataForDatabase')
                         ->with($data, $table)
                         ->willReturn($data);

        $this->queryBuilder->expects(self::once())
                           ->method('update')
                           ->with($table)
                           ->willReturn(self::returnSelf());

        $this->queryBuilder->expects(self::exactly(2))
                           ->method('set')
                           ->withConsecutive(['id', $data['id']], ['name', $data['name']])
                           ->willReturn(self::returnSelf());

        $this->queryBuilder->expects(self::exactly(2))
                           ->method('createNamedParameter')
                           ->willReturnOnConsecutiveCalls($data['id'], $data['name']);

        $this->queryBuilder->expects(self::once())
                           ->method('where')
                           ->with('id = :id')
                           ->willReturn(self::returnSelf());

        $this->queryBuilder->expects(self::once())
                           ->method('setParameter')
                           ->with('id', $id)
                           ->willReturn(self::returnSelf());

        $this->queryBuilder->expects(self::once())
                           ->method('executeStatement');

        $this->helper->update($data, $id, $table);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testInsertThrowExceptionIfDataIsEmpty(): void
    {
        $data   = [];
        $table  = 'tl_test';

        $this->dataHelper->expects(self::once())
                         ->method('filterDataForDatabase')
                         ->with($data, $table)
                         ->willReturn($data);

        $this->dataHelper->expects(self::once())
                         ->method('filterFieldsForInsert')
                         ->with($data, $table)
                         ->willReturn(\array_keys($data));

        $this->queryBuilder->expects(self::never())
                           ->method('insert');

        $this->queryBuilder->expects(self::never())
                           ->method('values');

        $this->queryBuilder->expects(self::never())
                           ->method('setParameters');

        $this->queryBuilder->expects(self::never())
                           ->method('executeStatement');

        $this->connectionHelper->expects(self::never())
                               ->method('getConnection');

        $this->expectException(NoDataFoundException::class);
        $this->expectExceptionMessage('data could not be convertet');

        $this->helper->insert($data, $table);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testInsertThrowExceptionIfFieldsNotFound(): void
    {
        $data   = ['id' => 12, 'name' => 'Test'];
        $table  = 'tl_test';

        $this->dataHelper->expects(self::once())
                         ->method('filterDataForDatabase')
                         ->with($data, $table)
                         ->willReturn($data);

        $this->dataHelper->expects(self::once())
                         ->method('filterFieldsForInsert')
                         ->with($data, $table)
                         ->willReturn([]);

        $this->queryBuilder->expects(self::never())
                           ->method('insert');

        $this->queryBuilder->expects(self::never())
                           ->method('values');

        $this->queryBuilder->expects(self::never())
                           ->method('setParameters');

        $this->queryBuilder->expects(self::never())
                           ->method('executeStatement');

        $this->connectionHelper->expects(self::never())
                               ->method('getConnection');

        $this->expectException(NoDataFoundException::class);
        $this->expectExceptionMessage('data could not be convertet');

        $this->helper->insert($data, $table);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testInsertCallQuery(): void
    {
        $data   = ['id' => 12, 'name' => 'Test'];
        $table  = 'tl_test';
        $id     = 34;

        $this->dataHelper->expects(self::once())
                         ->method('filterDataForDatabase')
                         ->with($data, $table)->willReturn($data);

        $this->dataHelper->expects(self::once())
                         ->method('filterFieldsForInsert')
                         ->with($data, $table)
                         ->willReturn(\array_keys($data));

        $this->queryBuilder->expects(self::once())
                           ->method('insert')
                           ->with($table)
                           ->willReturn(self::returnSelf());

        $this->queryBuilder->expects(self::once())
                           ->method('values')
                           ->with(\array_keys($data))
                           ->willReturn(self::returnSelf());

        $this->queryBuilder->expects(self::once())
                           ->method('setParameters')
                           ->with($data)
                           ->willReturn(self::returnSelf());

        $this->queryBuilder->expects(self::once())
                           ->method('executeStatement');

        $connection = $this->getMockBuilder(Connection::class)
                           ->disableOriginalConstructor()
                           ->getMock();

        $connection->expects(self::once())
                   ->method('lastInsertId')
                   ->willReturn($id);

        $this->connectionHelper->expects(self::once())
                               ->method('getConnection')
                               ->willReturn($connection);

        $rtn = $this->helper->insert($data, $table);

        self::assertSame($id, $rtn);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testDeleteThrowExceptionIfValueIsEmpty(): void
    {
        $value  = '';
        $field  = 'id';
        $table  = 'tl_test';

        $this->queryBuilder->expects(self::never())
                           ->method('delete');

        $this->queryBuilder->expects(self::never())
                           ->method('where');

        $this->queryBuilder->expects(self::never())
                           ->method('setParameter');

        $this->queryBuilder->expects(self::never())
                           ->method('executeStatement');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('parameter could not be empty');

        $this->helper->delete($value, $field, $table);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testDeleteThrowExceptionIfFieldIsEmpty(): void
    {
        $value  = '12';
        $field  = '';
        $table  = 'tl_test';

        $this->queryBuilder->expects(self::never())
                           ->method('delete');

        $this->queryBuilder->expects(self::never())
                           ->method('where');

        $this->queryBuilder->expects(self::never())
                           ->method('setParameter');

        $this->queryBuilder->expects(self::never())
                           ->method('executeStatement');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('parameter could not be empty');

        $this->helper->delete($value, $field, $table);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testDeleteThrowExceptionIfTableIsEmpty(): void
    {
        $value  = '12';
        $field  = 'id';
        $table  = '';

        $this->queryBuilder->expects(self::never())
                           ->method('delete');

        $this->queryBuilder->expects(self::never())
                           ->method('where');

        $this->queryBuilder->expects(self::never())
                           ->method('setParameter');

        $this->queryBuilder->expects(self::never())
                           ->method('executeStatement');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('parameter could not be empty');

        $this->helper->delete($value, $field, $table);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testDeleteCallQuery(): void
    {
        $value  = '12';
        $field  = 'id';
        $table  = 'tl_test';

        $this->queryBuilder->expects(self::once())
                           ->method('delete')
                           ->with($table)
                           ->willReturn(self::returnSelf());

        $this->queryBuilder->expects(self::once())
                           ->method('where')
                           ->with("$field = :$field")
                           ->willReturn(self::returnSelf());

        $this->queryBuilder->expects(self::once())
                           ->method('setParameter')
                           ->with($field, $value)
                           ->willReturn(self::returnSelf());

        $this->queryBuilder->expects(self::once())
                           ->method('executeStatement');

        $this->helper->delete($value, $field, $table);
    }
}
