<?php

/**
 * @package   Databaselayer
 * @since     22.09.2022 - 21:00
 * @author    Patrick Froch <info@easySolutionsIT.de>
 * @see       http://easySolutionsIT.de
 * @copyright e@sy Solutions IT 2022
 * @license   EULA
 */

declare(strict_types=1);

namespace Esit\Databaselayer\Classes\Services\Helper;

use Doctrine\DBAL\Exception;
use Esit\Databaselayer\Classes\Excaptions\InvalidArgumentException;

class QueryHelper extends AbstractHelper
{
    /**
     * Lädt Daten aus der Datenbank anhane eines bestimmten Werts (z.B. der Id).
     *
     * @param int|string $value
     * @param string $field
     * @param string $table
     * @param int $offset
     * @param int $limit
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
        if (empty($value) || empty($field) || empty($table)) {
            throw new InvalidArgumentException('parameter could not be empty');
        }

        $query = $this->connectionHelper->getQueryBuilder();

         $query->select('*')
               ->from($table)
               ->where("$field = :$field")
               ->setParameter($field, $value);

        return $this->execHelper->executeQuery($query, $offset, $limit);
    }


    /**
     * Lädt Werte aus einer Liste.
     *
     * @param array<string> $valueList
     * @param string $field
     * @param string $table
     * @param string $order
     * @param int $offset
     * @param int $limit
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
        if (empty($valueList) || empty($field) || empty($table)) {
            throw new InvalidArgumentException('parameter could not be empty');
        }

        $query = $this->connectionHelper->getQueryBuilder();

        $query->select('*')
              ->from($table)
              ->where($query->expr()->in('id', $valueList))
              ->orderBy($field, $order);

        return $this->execHelper->executeQuery($query, $offset, $limit);
    }


    /**
     * Lädt alle Daten einer Tabelle.
     *
     * @param string $table
     * @param string $orderField
     * @param string $order
     * @param int $offset
     * @param int $limit
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
        if (empty($table)) {
            throw new InvalidArgumentException('parameter could not be empty');
        }

        $query = $this->connectionHelper->getQueryBuilder();

        $query->select('*')
              ->from($table);

        if ('' !== $orderField) {
            $query->orderBy($orderField, $order);
        }

        return $this->execHelper->executeQuery($query, $offset, $limit);
    }
}
