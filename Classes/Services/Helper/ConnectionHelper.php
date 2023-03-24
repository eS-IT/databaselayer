<?php

/**
 * @package   Databaselayer
 * @since     22.09.2022 - 20:59
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

class ConnectionHelper
{


    /**
     * @var Connection
     */
    private Connection $connection;


    /**
     * DatabaseHelper constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }


    /**
     * Gibt die Connection zurück.
     *
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }


    /**
     * Setzt die Connection.
     * Wird für den Wechsel zur DB des API-Servers benötigt.
     * Im Demodatengenerator wird direkt auf diese Datenbank
     * zugegriffen.
     *
     * @param Connection $connection
     */
    public function setConnection(Connection $connection): void
    {
        $this->connection = $connection;
    }


    /**
     * Gibt den QueryBuilder zurück.
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        return $this->connection->createQueryBuilder();
    }


    /**
     * Gibt den SchemaManager zurück.
     *
     * @return AbstractSchemaManager
     * @throws Exception
     * @todo Kompatibilitätslayer entfernen, wenn Support für Contao 4.9 ausläuft!
     */
    public function getSchemaManager(): AbstractSchemaManager
    {
        if (\method_exists($this->connection, 'createSchemaManager')) {
            return $this->connection->createSchemaManager();
        }

        // Fallback für Contao 4.9
        return $this->connection->getSchemaManager();
    }
}
