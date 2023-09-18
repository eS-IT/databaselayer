<?php

/**
 * @package     Databaselayer
 * @since       24.03.2023 - 10:59
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2023
 * @license     EULA
 */

declare(strict_types=1);

namespace Esit\Databaselayer\Classes\Services\Helper;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Exception;

class ExecutionHelper
{
    /**
     * Führt eine Änderung an der Datenbank aus.
     * (Kompatibilitätslayer für Contao 4.9)
     *
     * @param QueryBuilder $query
     * @return int
     * @throws Exception
     * @todo Kompatibilitätslayer entfernen, wenn Support für Contao 4.9 ausläuft!
     */
    public function executeStatement(QueryBuilder $query): int
    {
        $conn = $query->getConnection();

        if (\method_exists($query, 'executeStatement')) {
            $query->executeStatement();

            return (int)$conn->lastInsertId();
        }

        // Fallback für Contao 4.9
        $query->execute();

        return (int)$conn->lastInsertId();
    }


    /**
     * Führt eine Abfrage auf der Datenbank aus.
     * (Kompatibilitätslayer für Contao 4.9)
     *
     * @param QueryBuilder $query
     * @param int $offset
     * @param int $limit
     * @return mixed[]
     * @throws Exception
     * @todo Kompatibilitätslayer entfernen, wenn Support für Contao 4.9 ausläuft!
     */
    public function executeQuery(QueryBuilder $query, int $offset = 0, int $limit = 0): array
    {
        if (0 !== $offset) {
            $query->setFirstResult($offset);
        }

        if (0 !== $limit) {
            $query->setMaxResults($limit);
        }

        if (\method_exists($query, 'executeQuery')) {
            return $query->executeQuery()->fetchAllAssociative();
        }

        // Fallback für Contao 4.9
        $result = $query->execute();

        return (\is_int($result) || \is_string($result)) ? [$result] : $result->fetchAllAssociative();
    }
}
