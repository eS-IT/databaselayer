<?php

/**
 * @package   Databaselayer
 * @since     23.09.2022 - 11:41
 * @author    Patrick Froch <info@easySolutionsIT.de>
 * @see       http://easySolutionsIT.de
 * @copyright e@sy Solutions IT 2022
 * @license   EULA
 */

declare(strict_types=1);

namespace Esit\Databaselayer\Classes\Services\Helper;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Schema\AbstractSchemaManager;

class DatabaseHelper
{
    /**
     * @var ConnectionHelper
     */
    private ConnectionHelper $connectionHelper;


    /**
     * @var QueryHelper
     */
    private QueryHelper $queryHelper;


    /**
     * @var StatementHelper
     */
    private StatementHelper $statementHelper;


    /**
     * @param QueryHelper     $queryHelper
     * @param StatementHelper $statementHelper
     */
    public function __construct(
        ConnectionHelper $connectionHelper,
        QueryHelper $queryHelper,
        StatementHelper $statementHelper
    ) {
        $this->connectionHelper = $connectionHelper;
        $this->queryHelper      = $queryHelper;
        $this->statementHelper  = $statementHelper;
    }


    /**
     * Fassade für ConnectionHelper::getConnection().
     *
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->connectionHelper->getConnection();
    }


    /**
     * Fassade für ConnectionHelper::getQueryBuilder().
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        return $this->connectionHelper->getQueryBuilder();
    }


    /**
     * Fassade für ConnectionHelper::getSchemaManager().
     *
     * @return AbstractSchemaManager
     * @throws Exception
     */
    public function getSchemaManager(): AbstractSchemaManager
    {
        return $this->connectionHelper->getSchemaManager();
    }


    /**
     * Fassade für QueryHelper::loadByValue().
     *
     * @param int|string $value
     * @param string     $field
     * @param string     $table
     * @param int        $offset
     * @param int        $limit
     * @return mixed[]
     * @throws Exception
     */
    public function loadByValue(
        int|string $value,
        string $field,
        string $table,
        int $offset = 0,
        int $limit = 0
    ): array {
        return $this->queryHelper->loadByValue($value, $field, $table, $offset, $limit);
    }


    /**
     * Fassade für QueryHelper::loadByList().
     *
     * @param array<string> $valueList
     * @param string        $field
     * @param string        $table
     * @param string        $order
     * @param int           $offset
     * @param int           $limit
     * @return mixed[]
     * @throws Exception
     */
    public function loadByList(
        array $valueList,
        string $field,
        string $table,
        string $order = 'ASC',
        int $offset = 0,
        int $limit = 0
    ): array {
        return $this->queryHelper->loadByList($valueList, $field, $table, $order, $offset, $limit);
    }


    /**
     * Fassade für QueryHelper::loadAll().
     *
     * @param string $table
     * @param string $orderField
     * @param string $order
     * @param int    $offset
     * @param int    $limit
     * @return mixed[]
     * @throws Exception
     */
    public function loadAll(
        string $table,
        string $orderField = '',
        string $order = 'ASC',
        int $offset = 0,
        int $limit = 0
    ): array {
        return $this->queryHelper->loadAll($table, $orderField, $order, $offset, $limit);
    }


    /**
     * Fassade für StatementHelper::insert().
     *
     * @param  mixed[]  $data
     * @param  string   $table
     * @return int
     * @throws Exception
     */
    public function insert(array $data, string $table): int
    {
        return $this->statementHelper->insert($data, $table);
    }


    /**
     * Fassade für StatementHelper::update().
     *
     * @param  mixed[]  $data
     * @param  int      $id
     * @param  string   $table
     * @return void
     * @throws Exception
     */
    public function update(array $data, int $id, string $table): void
    {
        $this->statementHelper->update($data, $id, $table);
    }


    /**
     * Fassade für StatementHelper::insert().
     *
     * @param  string $value
     * @param  string $field
     * @param  string $table
     * @return void
     * @throws Exception
     */
    public function delete(string $value, string $field, string $table): void
    {
        $this->statementHelper->delete($value, $field, $table);
    }
}
