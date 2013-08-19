<?php

include_once("../src/SQL/DB.php");
include_once("../src/SQL/SQL.php");
include_once("../src/RandomReaders/RandomReader.php");

class SqlRandomReader implements RandomReader{

    private $connection = null;

    function __construct ($ip, $user, $password, $database, $table)
    {
        $this->connection = new DB($ip, $user, $password, $database);
        $this->table = $table;
    }

    function readRow($index) {
        $query = SQL::select($this->table, null, null, 1, $index);
        $res = $this->connection->query($query);
        return $res[0];
    }

    function getRowCount() {
        $query = SQL::select($this->table, "COUNT(*)", null);
        $rowCount = $this->connection->query($query);

        return $rowCount[0]["COUNT(*)"];
    }

}