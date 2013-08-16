<?php


include_once("../src/SQL/DB.php");
include_once("../src/SQL/Table.php");
include_once("../src/SQL/SQL.php");
include_once("../src/Writers/Writer.php");
include_once("../src/Writers/WriterException.php");

class SqlWriter implements Writer{
    private $connection = null;
    private $defaultDataType = "varchar(100)";

    function __construct($ip, $user, $password, $database, $table) {
        $this->connection = new DB($ip, $user, $password, $database);
        $this->table = $table;
    }

    function writeRow($data) {
        $this->createTableIfNotExists($data);
        $this->createColumnsIfNotExist($data);
        $query = SQL::insert($this->table, $data);
        $this->connection->query($query);
    }

    private function createTableIfNotExists($data) {
        $tableExists = in_array($this->table, \Table::getAvailable($this->connection));

        $columns = array();
        foreach ($data as $columnName => $value){
            $columns[$columnName] = $this->defaultDataType;
        }

        if (!$tableExists)
        {
            $query = \SQL::createTable($this->table, $columns);
            $this->connection->query($query);
        }
    }

    private function createColumnsIfNotExist($data) {
        foreach($data as $key => $value)
        {
            $columnExists = in_array("$key", \Table::getColumns($this->table, $this->connection));
            if(!$columnExists)
            {
                $query = \SQL::addColumn($this->table, $key, $this->defaultDataType);
                $this->connection->query($query);
            }
        }
    }

}