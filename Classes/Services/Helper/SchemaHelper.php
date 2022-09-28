<?php

/**
 * @package   Databaselayer
 * @since     22.09.2022 - 21:16
 * @author    Patrick Froch <info@easySolutionsIT.de>
 * @see       http://easySolutionsIT.de
 * @copyright e@sy Solutions IT 2022
 * @license   EULA
 */

declare(strict_types=1);

namespace Esit\Databaselayer\Classes\Services\Helper;

use Doctrine\DBAL\Exception;
use Esit\Databaselayer\Classes\Excaptions\InvalidArgumentException;

class SchemaHelper extends AbstractHelper
{


    /**
     * Gibt ein Array mit den Namen der Datenbanken zurück.
     *
     * @return array
     * @throws Exception
     */
    public function getDatabases(): array
    {
        return $this->connectionHelper->getSchemaManager()->listDatabases();
    }


    /**
     * Gibt ein Array mit den Namen der Tabellen zurück.
     *
     * @return array
     * @throws Exception
     */
    public function getTables(): array
    {
        $tablesList = $this->connectionHelper->getSchemaManager()->listTables();
        $tables     = [];

        foreach ($tablesList as $tab) {
            $tables[] = $tab->getName();
        }

        return $tables;
    }


    /**
     * Gibt ein Array mit den Spalten einer Tabelle zurück.
     *
     * @param  string $table
     * @return array
     * @throws Exception
     */
    public function getColumns(string $table): array
    {
        if (empty($table)) {
            throw new InvalidArgumentException('parameter could not be empty');
        }

        $columns    = [];
        $columnList = $this->connectionHelper->getSchemaManager()->listTableColumns($table);

        foreach ($columnList as $col) {
            $columns[] = $col->getName();
        }

        return $columns;
    }
}
