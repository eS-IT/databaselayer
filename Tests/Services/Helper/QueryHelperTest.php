<?php

/**
 * @package     Databaselayer
 * @since       27.09.2022 - 15:17
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2022
 * @license     EULA
 */

declare(strict_types=1);

namespace Esit\Databaselayer\Tests\Services\Helper;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Result;

use Esit\Databaselayer\Classes\Excaptions\InvalidArgumentException;
use Esit\Databaselayer\Classes\Services\Helper\ConnectionHelper;
use Esit\Databaselayer\Classes\Services\Helper\ExecutionHelper;
use Esit\Databaselayer\Classes\Services\Helper\QueryHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class QueryHelperTest extends TestCase
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
     * @var (ExecutionHelper&MockObject)|MockObject
     */
    private $execHelper;


    /**
     * @var QueryHelper
     */
    private QueryHelper $helper;


    protected function setUp(): void
    {
        $this->connectionHelper = $this->getMockBuilder(ConnectionHelper::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->queryBuilder     = $this->getMockBuilder(QueryBuilder::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->execHelper       = $this->getMockBuilder(ExecutionHelper::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->helper           = new QueryHelper($this->connectionHelper, $this->execHelper);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testLoadByValueThrowExceptionIfValueIsEmpty(): void
    {
        $value  = '';
        $field  = 'id';
        $table  = 'tl_test';

        $this->connectionHelper->expects(self::never())
                               ->method('getQueryBuilder');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('parameter could not be empty');
        $this->helper->loadByValue($value, $field, $table);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testLoadByValueThrowExceptionIfFieldIsEmpty(): void
    {
        $value  = '12';
        $field  = '';
        $table  = 'tl_test';

        $this->connectionHelper->expects(self::never())
                               ->method('getQueryBuilder');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('parameter could not be empty');
        $this->helper->loadByValue($value, $field, $table);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testLoadByValueThrowExceptionIfTableIsEmpty(): void
    {
        $value  = '12';
        $field  = 'id';
        $table  = '';

        $this->connectionHelper->expects(self::never())
                               ->method('getQueryBuilder');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('parameter could not be empty');
        $this->helper->loadByValue($value, $field, $table);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testLoadByValueReturnRow(): void
    {
        $value  = '12';
        $field  = 'id';
        $table  = 'tl_test';
        $row    = ['id' => 12, 'name' => 'Test'];

        $this->connectionHelper->expects(self::once())
                               ->method('getQueryBuilder')
                               ->willReturn($this->queryBuilder);

        $this->queryBuilder->expects(self::once())
                           ->method('select')
                           ->with('*')
                           ->willReturn(self::returnSelf());

        $this->queryBuilder->expects(self::once())
                           ->method('from')
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

        $this->execHelper->expects(self::once())
                         ->method('executeQuery')
                         ->willReturn($row);

        $rtn = $this->helper->loadByValue($value, $field, $table);

        self::assertSame($row, $rtn);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testLoadByListThrowExceptionIfValueIsEmpty(): void
    {
        $value  = [];
        $field  = 'id';
        $table  = 'tl_test';

        $this->connectionHelper->expects(self::never())
                               ->method('getQueryBuilder');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('parameter could not be empty');
        $this->helper->loadByList($value, $field, $table);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testLoadByListThrowExceptionIfFieldIsEmpty(): void
    {
        $value  = ['12', '34'];
        $field  = '';
        $table  = 'tl_test';

        $this->connectionHelper->expects(self::never())
                               ->method('getQueryBuilder');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('parameter could not be empty');
        $this->helper->loadByList($value, $field, $table);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testLoadByListThrowExceptionIfTableIsEmpty(): void
    {
        $value  = ['12', '34'];
        $field  = 'id';
        $table  = '';

        $this->connectionHelper->expects(self::never())
                               ->method('getQueryBuilder');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('parameter could not be empty');
        $this->helper->loadByList($value, $field, $table);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testLoadByListReturnRows(): void
    {
        $value  = ['12', '34'];
        $field  = 'id';
        $table  = 'tl_test';
        $order  = 'DESC';
        $row    = ['id' => 12, 'name' => 'Test'];

        $this->connectionHelper->expects(self::once())
                               ->method('getQueryBuilder')
                               ->willReturn($this->queryBuilder);

        $this->queryBuilder->expects(self::once())
                           ->method('select')
                           ->with('*')
                           ->willReturn(self::returnSelf());

        $this->queryBuilder->expects(self::once())
                           ->method('from')
                           ->with($table)
                           ->willReturn(self::returnSelf());

        $this->queryBuilder->expects(self::once())
                           ->method('where')
                           ->with("$field IN (:$field)")
                           ->willReturn(self::returnSelf());

        $this->queryBuilder->expects(self::once())
                           ->method('setParameter')
                           ->with($field, \implode(',', $value))
                           ->willReturn(self::returnSelf());

        $this->queryBuilder->expects(self::once())
                           ->method('orderBy')
                           ->with($field, $order)
                           ->willReturn(self::returnSelf());

        $this->execHelper->expects(self::once())
                         ->method('executeQuery')
                         ->willReturn([$row, $row]);

        $rtn = $this->helper->loadByList($value, $field, $table, $order);

        self::assertSame([$row, $row], $rtn);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testLaodAllThrowsExceptionIfTableIsEmpty(): void
    {
        $table = '';

        $this->connectionHelper->expects(self::never())
                               ->method('getQueryBuilder');

        $this->queryBuilder->expects(self::never())
                           ->method('select');

        $this->queryBuilder->expects(self::never())
                           ->method('from');

        $this->queryBuilder->expects(self::never())
                           ->method('orderBy');

        $this->execHelper->expects(self::never())
                         ->method('executeQuery');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('parameter could not be empty');
        $this->helper->loadAll($table);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testLaodAllReturnsArrayIfDataLoaded(): void
    {
        $table  = 'tl_test';
        $row    = ['id' => 12, 'name' => 'Test'];

        $this->connectionHelper->expects(self::once())
                               ->method('getQueryBuilder')
                               ->willReturn($this->queryBuilder);

        $this->queryBuilder->expects(self::once())
                           ->method('select')
                           ->with('*')
                           ->willReturn($this->queryBuilder);

        $this->queryBuilder->expects(self::once())
                           ->method('from')
                           ->with($table)
                           ->willReturn($this->queryBuilder);

        $this->queryBuilder->expects(self::never())
                           ->method('orderBy');

        $this->execHelper->expects(self::once())
                         ->method('executeQuery')
                         ->with($this->queryBuilder)
                         ->willReturn($row);

        self::assertSame($row, $this->helper->loadAll($table));
    }


    /**
     * @return void
     * @throws Exception
     */
    public function testLaodAllSetOrderIfOrderFieldIsGiven(): void
    {
        $table      = 'tl_test';
        $order      = 'DESC';
        $orderField = 'id';
        $row        = ['id' => 12, 'name' => 'Test'];

        $this->connectionHelper->expects(self::once())
                               ->method('getQueryBuilder')
                               ->willReturn($this->queryBuilder);

        $this->queryBuilder->expects(self::once())
                           ->method('select')
                           ->with('*')
                           ->willReturn($this->queryBuilder);

        $this->queryBuilder->expects(self::once())
                           ->method('from')
                           ->with($table)
                           ->willReturn($this->queryBuilder);

        $this->queryBuilder->expects(self::once())
                           ->method('orderBy')
                           ->with($orderField, $order)
                           ->willReturn($this->queryBuilder);

        $this->execHelper->expects(self::once())
                         ->method('executeQuery')
                         ->with($this->queryBuilder)
                         ->willReturn($row);

        self::assertSame($row, $this->helper->loadAll($table, $orderField, $order));
    }
}
