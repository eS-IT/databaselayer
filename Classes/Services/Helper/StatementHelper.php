<?php

/**
 * @package   Databaselayer
 * @since     22.09.2022 - 21:13
 * @author    Patrick Froch <info@easySolutionsIT.de>
 * @see       http://easySolutionsIT.de
 * @copyright e@sy Solutions IT 2022
 * @license   EULA
 */

declare(strict_types=1);

namespace Esit\Databaselayer\Classes\Services\Helper;

use Doctrine\DBAL\Exception;
use Esit\Databaselayer\Classes\Excaptions\InvalidArgumentException;
use Esit\Databaselayer\Classes\Excaptions\NoDataFoundException;

class StatementHelper extends AbstractHelper
{


    /**
     * @var DataHelper
     */
    private DataHelper $dataHelper;


    /**
     * @param ConnectionHelper $conHelper
     * @param DataHelper       $dataHelper
     */
    public function __construct(ConnectionHelper $conHelper, DataHelper $dataHelper)
    {
        parent::__construct($conHelper);
        $this->dataHelper = $dataHelper;
    }


    /**
     * Ändert einen Datensatz in der Db.
     *
     * @param  array  $data
     * @param  int    $id
     * @param  string $table
     * @throws Exception
     */
    public function update(array $data, int $id, string $table): void
    {
        if (empty($data) || empty($id) || empty($table)) {
            throw new InvalidArgumentException('parameter could not be empty');
        }

        $data   = $this->dataHelper->filterDataForDatabase($data, $table);
        $query  = $this->connectionHelper->getQueryBuilder();
        $query->update($table);

        foreach ($data as $k => $v) {
            $query->set($k, $query->createNamedParameter($v));
        }

        $query->where("id = :id")
            ->setParameter('id', $id)
            ->executeStatement();
    }


    /**
     * Fügt einen neuen Datensatz in die Db ein.
     *
     * @param  array  $data
     * @param  string $table
     * @throws Exception
     * @return int
     */
    public function insert(array $data, string $table): int
    {
        $query  = $this->connectionHelper->getQueryBuilder();
        $data   = $this->dataHelper->filterDataForDatabase($data, $table);
        $fields = $this->dataHelper->filterFieldsForInsert($data, $table);

        if (empty($data) || empty($fields)) {
            throw new NoDataFoundException('data could not be convertet');
        }

        $query->insert($table)
            ->values($fields)
            ->setParameters($data)
            ->executeStatement();

        return (int)$this->connectionHelper->getConnection()
            ->lastInsertId();
    }


    /**
     * Löscht einen Datensatz aus der Datenbank.
     *
     * @param  string $value
     * @param  string $field
     * @param  string $table
     * @return void
     * @throws Exception
     */
    public function delete(string $value, string $field, string $table): void
    {
        if (empty($value) || empty($field) || empty($table)) {
            throw new InvalidArgumentException('parameter could not be empty');
        }

        $query = $this->connectionHelper->getQueryBuilder();
        $query->delete($table)
            ->where("$field = :$field")
            ->setParameter($field, $value)
            ->executeStatement();
    }
}
