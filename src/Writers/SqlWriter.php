<?php


include_once("../src/SQL/DB.php");
include_once("../src/SQL/Table.php");
include_once("../src/SQL/SQL.php");
include_once("../src/Writers/Writer.php");
include_once("../src/Writers/WriterException.php");

class SqlWriter implements Writer{
    private $connection = null;

    function __construct($ip, $user, $password, $database, $table) {
        $this->connection = new DB($ip, $user, $password, $database);
        $this->table = $table;

        $this->tableExists = in_array($table, Table::getAvailable($this->connection));
    }

    function writeRow($data) {
        $this->createTableIfNotExists($this->tableExists, $data);

        $query = SQL::insert($this->table, $data);
        $this->connection->query($query);
    }

    private function getTableExists() {
        return $this->tableExists;
    }

    private function createTableIfNotExists($tableExists, $data) {
        if (!$tableExists)
        {
            $query = SQL::createTable($this->table, $data);
            $this->connection->query($query);
            $this->tableExists = true;
        }
    }

    private function checkIfAllColumnsExist() {

    }
}