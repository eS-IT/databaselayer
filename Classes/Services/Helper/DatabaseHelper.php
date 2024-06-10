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
     * @var DateHelper
     */
    private DateHelper $dateHelper;


    /**
     * @param ConnectionHelper $connectionHelper
     * @param QueryHelper $queryHelper
     * @param StatementHelper $statementHelper
     * @param DateHelper $dateHelper
     */
    public function __construct(
        ConnectionHelper $connectionHelper,
        QueryHelper $queryHelper,
        StatementHelper $statementHelper,
        DateHelper $dateHelper
    ) {
        $this->connectionHelper = $connectionHelper;
        $this->queryHelper      = $queryHelper;
        $this->statementHelper  = $statementHelper;
        $this->dateHelper       = $dateHelper;
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
     * @param string[] $valueList
     * @param string $orderField
     * @param string $table
     * @param string $order
     * @param int $offset
     * @param int $limit
     * @param string $searchField
     * @return mixed[]
     * @throws Exception
     */
    public function loadByList(
        array $valueList,
        string $orderField,
        string $table,
        string $order = 'ASC',
        int $offset = 0,
        int $limit = 0,
        string $searchField = 'id'
    ): array {
        return $this->queryHelper->loadByList($valueList, $orderField, $table, $order, $offset, $limit, $searchField);
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


    /**
     * Gibt die Felder einer Tabelle zurück.
     *
     * @param string $table
     * @return mixed[]
     * @throws \Doctrine\DBAL\Exception
     */
    public function getTableFields(string $table): array
    {
        $fields     = [];
        $columns    = $this->getSchemaManager()->listTableColumns($table) ?: [];

        foreach ($columns as $column) {
            $fields[] = $column->getName();
        }

        return $fields;
    }


    /**
     * Gibt nur die Daten der Felder zurück, die auch in der übergebenen Tabelle enthalten sind.
     *
     * @param string  $table
     * @param mixed[] $data
     * @return mixed[]
     * @throws \Doctrine\DBAL\Exception
     */
    public function filterDataForDb(string $table, array $data): array
    {
        if (empty($data)) {
            return $data;
        }

        $dbData = [];
        $fields = $this->getTableFields($table);

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $dbData[$field] = $data[$field];
            }
        }

        return $dbData;
    }


    /**
     * Speichert einen Datensatz.
     *
     * @param string  $table
     * @param mixed[] $data
     * @return int
     * @throws \Doctrine\DBAL\Exception
     */
    public function save(string $table, array $data): int
    {
        $data['tstamp'] = $this->dateHelper->getTimestamp();
        $data           = $this->filterDataForDb($table, $data);

        if (empty($data['id'])) {
            return $this->insert($data, $table);
        }

        $id = (int) $data['id'];
        unset($data['id']);
        $this->update($data, $id, $table);

        return $id;
    }
}
