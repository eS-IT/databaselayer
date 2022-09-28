# Databaselayer


## Beschreibung

Bei dieser Software handelt es sich um eine Erweiterung für das Open Source CMS Contao, die Zugriff auf die Datenbank
vereinfacht.


## Autor

__e@sy Solutions IT:__ Patrick Froch <info@easySolutionsIT.de>


## Lizenz

Die Software wird unter LGPL veröffentlicht. Details sind in der Datei `LICENSE` zu finden.


## Voraussetzungen

- php: ^8.0
- contao/core-bundle:~4.9


## Installation

Die Erweiterung kann einfach über den ContaoManager installiert werden. Einfach nach `esit/databaselayer` suchen und
installieren.


## Verwendung

Da die Funktionalität auf verschiedene Klassen aufgeteilt ist, gibt es eine Fassade, die die Funktion bündelt.
Für die Verwendung der Erweiterung wird fast ausschließlich der `DatabaseHelper` verwendet. Dieser ermöglicht einfache
Datenbankoperationen wie `loadByValue`, `loadByList`, `insert`, `update` und `delete`. Wenn man komplexere Abfragen
benötigt, kann man sich mit `getQueryBuilder` einen `QueryBuilder` geben lassen und die Abfrage manuell erstellen.

```php
use \Esit\Databaselayer\Classes\Services\Helper\DatabaseHelper;

myClass
{

    private DatabaseHelper $dbHelper;

    public function __construct(DatabaseHelper $dbHelper)
    {
        $this->dbHelper = $dbHelper;
    }

    public function myTest(): void
    {
        // lesende Operationen
        $row        = $this->dbHelper->loadByValue(12, 'id', 'tl_member');
        $collection = $this->dbHelper->loadByList([12, 13, 14 ,15], 'id', 'tl_member');

        // schreibende Operationen
        unset($row['id']);  // Id muss unique sein!
        $id = $this->dbHelper->insert($row, 'tl_member');
        $this->dbHelper->update($row, 16, 'tl_member');
        $this->dbHelper->delete(16, 'tl_member');

        // QueryBuilder
        $query  = $this->dbHelper->getQueryBuilder();
        $query->select('*')->from('tl_member')->where->('id > :id')->setParameter('id', 12);
        $result = $query->executeQuery();
    }
}
```
