<?php

include_once("../src/SQL/DB.php");
include_once("../src/SQL/SQL.php");
include_once("../src/RandomReaders/RandomReader.php");

class SqlRandomReader implements RandomReader{

    private $connection = null;
    private $table;
    private $tableName;

    function __construct ($ip, $user, $password, $database, $table)
    {
        $this->tableName = $table;
        $this->connection = new DB($ip, $user, $password, $database);
        $this->table = new Table($this->connection, $this->tableName);
    }

    function readRow($index) {
        $res = $this->table->search(null, 1, $index);
        return $res[0];
    }

    function getRowCount() {
        $query = SQL::select($this->tableName, "COUNT(*)", null);
        $rowCount = $this->connection->query($query);

        return intval($rowCount[0]["COUNT(*)"]);
    }

}