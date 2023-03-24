<?php

/**
 * @package     Databaselayer
 * @since       23.09.2022 - 13:56
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2022
 * @license     EULA
 */

declare(strict_types=1);

namespace Esit\Databaselayer\Tests\Services\Helper;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Esit\Databaselayer\Classes\Services\Helper\ConnectionHelper;
use PHPUnit\Framework\TestCase;

class ConnectionHelperTest extends TestCase
{


    /**
     * @var ConnectionHelper
     */
    private ConnectionHelper $helper;


    /**
     * @var Connection|Connection&\PHPUnit\Framework\MockObject\MockObject|\PHPUnit\Framework\MockObject\MockObject
     */
    private Connection $connection;


    protected function setUp(): void
    {
        $this->connection   = $this->getMockBuilder(Connection::class)
                                   ->disableOriginalConstructor()
                                   ->getMock();

        $this->helper       = new ConnectionHelper($this->connection);
    }


    public function testGetConnection(): void
    {
        self::assertSame($this->connection, $this->helper->getConnection());
    }


    public function testSetConnection(): void
    {
        self::assertSame($this->connection, $this->helper->getConnection());

        $connection   = $this->getMockBuilder(Connection::class)
                             ->disableOriginalConstructor()
                             ->getMock();

        $this->helper->setConnection($connection);
        self::assertSame($connection, $this->helper->getConnection());
    }


    public function testGetQueryBuilder(): void
    {
        $query = $this->getMockBuilder(QueryBuilder::class)
                      ->disableOriginalConstructor()
                      ->getMock();

        $this->connection->expects(self::once())
                         ->method('createQueryBuilder')
                         ->willReturn($query);

        self::assertSame($query, $this->helper->getQueryBuilder());
    }


    public function testGetSchemaManager(): void
    {
        $schema = $this->getMockBuilder(AbstractSchemaManager::class)
                       ->disableOriginalConstructor()
                       ->getMock();

        if (\method_exists($this->connection, 'createSchemaManager')) {
            $this->connection->expects(self::once())
                             ->method('createSchemaManager')
                             ->willReturn($schema);

            $this->connection->expects(self::never())
                             ->method('getSchemaManager');
        }

        if (\method_exists($this->connection, 'getSchemaManager')) {
            $this->connection->expects(self::never())
                             ->method('createSchemaManager');

            $this->connection->expects(self::once())
                             ->method('getSchemaManager')
                             ->willReturn($schema);
        }

        self::assertSame($schema, $this->helper->getSchemaManager());
    }
}
