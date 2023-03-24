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
     * @param QueryBuilder $query
     * @return int
     * @throws Exception
     */
    public function executeStatement(QueryBuilder $query): int
    {
        $conn = $query->getConnection();

        if (\method_exists($query, 'executeStatement')) {
            $query->executeStatement();

            return $conn->lastInsertId();
        }

        // Fallback für Contao 4.9
        $query->execute();

        return $conn->lastInsertId();
    }


    /**
     * Führt eine Abfrage auf der Datenbank aus.
     * (Kompatibilitätslayer für Contao 4.9)
     * @param QueryBuilder $query
     * @return array
     * @throws Exception
     */
    public function executeQuery(QueryBuilder $query): array
    {
        if (\method_exists($query, 'executeQuery')) {
            return $query->executeQuery()->fetchAllAssociative();
        }

        // Fallback für Contao 4.9
        return $query->execute()->fetchAll(\PDO::FETCH_ASSOC);
    }
}
