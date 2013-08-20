<?php


include_once("../src/SQL/DB.php");
include_once("../src/SQL/Table.php");
include_once("../src/SQL/SQL.php");
include_once("../src/Writers/Writer.php");
include_once("../src/Writers/WriterException.php");

class SqlWriter implements Writer{
    private $connection = null;
    private $defaultDataType = "varchar(100)";

    private $table;
    private $tableName;
    private $tableExists = false;

    function __construct($ip, $user, $password, $database, $table) {
        $this->connection = new DB($ip, $user, $password, $database);
        $this->tableName = $table;
        $this->tableExists = in_array($this->tableName, Table::getAvailable($this->connection));
        if ($this->tableExists){
            $this->table = new Table($this->connection, $this->tableName);
        }
    }

    function writeRow($data) {
        $this->tableExists = in_array($this->tableName, Table::getAvailable($this->connection));
        if (!$this->tableExists) {
            $this->createTable($data);
        }
        $this->createColumnsIfNotExist($data);

        $this->table->insert($data);
    }

    private function createTable($data) {
        $columns = array();
        foreach ($data as $columnName => $value){
            $columns[$columnName] = $this->defaultDataType;
        }

        $this->table = Table::create($this->connection, $this->tableName, $columns);
    }

    private function createColumnsIfNotExist($data) {
        $arrayColumns = $this->table->getColumns();

        foreach ($data as $columnName => $value) {
            $columnExists = in_array("$columnName", $arrayColumns);
            if (!$columnExists) {
                $this->table->addColumn($columnName, $this->defaultDataType);
            }
        }
    }

}