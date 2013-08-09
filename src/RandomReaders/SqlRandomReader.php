<?php

include_once("../SQL/DB.php");
include_once("../SQL/SQL.php");
include_once("RandomReader.php");

class SqlReader implements RandomReader{

    private $connection = null;

    function __construct ($ip, $user, $password, $database, $table)
    {
        $this->connection = new DB($ip, $user, $password, $database);
        $this->table = $table;
    }

    function readRow($index) {
        $query = SQL::select($this->table, null, null, 1, $index);
        return $this->connection->query($query);
    }

    function getRowCount() {
        $query = SQL::select($this->table, "COUNT(*)", null);
        $rowCount = $this->connection->query($query);

        return $rowCount[0]["COUNT(*)"];
    }

}