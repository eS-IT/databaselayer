<?php
/**
 * @package     Databaselayer
 * @since       24.03.2023 - 11:42
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2023
 * @license     EULA
 */
declare(strict_types=1);
namespace Esit\Databaselayer\Tests\Services\Helper;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Result;
use Esit\Databaselayer\Classes\Services\Helper\ExecutionHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ExecutionHelperTest extends TestCase
{


    /**
     * @var (QueryBuilder&MockObject)|MockObject
     */
    private $query;


    /**
     * @var (Connection&MockObject)|MockObject
     */
    private $connection;


    /**
     * @var (Result&MockObject)|MockObject
     */
    private $result;


    /**
     * @var ExecutionHelper
     */
    private ExecutionHelper $execHelper;


    protected function setUp(): void
    {
        $this->query        = $this->getMockBuilder(QueryBuilder::class)
                                   ->disableOriginalConstructor()
                                   ->getMock();

        $this->connection   = $this->getMockBuilder(Connection::class)
                                   ->disableOriginalConstructor()
                                   ->getMock();

        $this->result       = $this->getMockBuilder(Result::class)
                                   ->disableOriginalConstructor()
                                   ->getMock();

        $this->execHelper   = new ExecutionHelper();
    }


    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function testExecuteStatementCallRightMethode(): void
    {
        $id = 12;

        $this->connection->expects(self::once())
                         ->method('lastInsertId')
                         ->willReturn($id);

        $this->query->expects(self::once())
                    ->method('getConnection')
                    ->willReturn($this->connection);

        if (\method_exists($this->query, 'executeStatement')) {
            $this->query->expects(self::once())
                        ->method('executeStatement');

            $this->query->expects(self::never())
                        ->method('execute');
        }

        if (!\method_exists($this->query, 'executeStatement')) {

            $this->query->expects(self::once())
                        ->method('execute');
        }

        self::assertSame($id, $this->execHelper->executeStatement($this->query));
    }


    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function testExecuteQueryCallRightMethode(): void
    {
        $row = ['test'];

        $this->result->expects(self::once())
                     ->method('fetchAllAssociative')
                     ->willReturn($row);

        if (\method_exists($this->query, 'executeQuery')) {
            $this->query->expects(self::once())
                        ->method('executeQuery')
                        ->willReturn($this->result);

            $this->query->expects(self::never())
                        ->method('execute');
        }

        if (!\method_exists($this->query, 'executeQuery')) {
            $this->query->expects(self::once())
                        ->method('execute')
                        ->willReturn($this->result);
        }

        self::assertSame($row, $this->execHelper->executeQuery($this->query));
    }
}
