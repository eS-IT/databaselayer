<?php

/**
 * @package   Databaselayer
 * @since     18.12.19 - 15:55
 * @author    Patrick Froch <info@easySolutionsIT.de>
 * @see       http://easySolutionsIT.de
 * @copyright e@sy Solutions IT 2019
 * @license   EULA
 */

declare(strict_types=1);

namespace Esit\Databaselayer\Classes\Services\Helper;

use Doctrine\DBAL\Exception;
use Esit\Databaselayer\Classes\Excaptions\InvalidArgumentException;

class DataHelper
{


    /**
     * @var SchemaHelper
     */
    private SchemaHelper $schemaHelper;


    /**
     * @param SchemaHelper $schemaHelper
     */
    public function __construct(SchemaHelper $schemaHelper)
    {
        $this->schemaHelper = $schemaHelper;
    }


    /**
     * Gibt nur die Daten zurück, für die es auch Felder in der Datenbanktabelle gibt.
     *
     * @param  array  $row
     * @param  string $table
     * @return array
     * @throws Exception
     */
    public function filterDataForDatabase(array $row, string $table): array
    {
        $dbData     = [];
        $dbFields   = $this->schemaHelper->getColumns($table);

        if (empty($row) || empty($table)) {
            throw new InvalidArgumentException('parameter could not be empty');
        }

        foreach ($row as $key => $value) {
            if (true === \in_array($key, $dbFields, true)) {
                // Felder übernehmen, die in tl_member vorkommen!
                $dbData[$key] = $value;
            }
        }

        return $dbData;
    }


    /**
     * Gibt ein Array mit den Namen der Felder für ein Insert-Statement zurück.
     *
     * @param  array  $row
     * @param  string $table
     * @return array
     */
    public function filterFieldsForInsert(array $row, string $table): array
    {
        if (empty($row) || empty($table)) {
            throw new InvalidArgumentException('parameter could not be empty');
        }

        $fields = [];

        foreach ($row as $k => $v) {
            $fields[$k] = ":$k";
        }

        return $fields;
    }
}
