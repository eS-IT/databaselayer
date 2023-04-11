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
     * @param  int|string $value
     * @param  string     $field
     * @param  string     $table
     * @return mixed[]
     * @throws Exception
     */
    public function loadByValue(int|string $value, string $field, string $table): array
    {
        return $this->queryHelper->loadByValue($value, $field, $table);
    }


    /**
     * Fassade für QueryHelper::loadByList().
     *
     * @param  mixed[]  $valueList
     * @param  string   $field
     * @param  string   $table
     * @param  string   $order
     * @return mixed[]
     * @throws Exception
     */
    public function loadByList(array $valueList, string $field, string $table, string $order = 'ASC'): array
    {
        return $this->queryHelper->loadByList($valueList, $field, $table, $order);
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
