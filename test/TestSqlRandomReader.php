<?php
namespace Enhance;

include_once("../src/SQL/DB.php");
include_once("../src/SQL/SQL.php");
include_once("../src/SQL/Table.php");
include_once("../src/RandomReaders/SqlRandomReader.php");

class TestSqlRandomReader extends TestFixture{
    private $connection;
    private $table;
    private $defaultDataType = "varchar(100)";
    private $tableName = "testSqlRandomReader";
    private $data = array (
        array (
            "0" => "",
            "1" => "Finchatton",
            "2" => "",
            "3" => "Adam",
            "4" => "Hunter",
            "5" => "www.amayadesign.co.uk/AdamHunter",
            "6" => "www.amayadesign.co.uk/",
            "7" => "AdamHunter",
            "8" => "Y",
            "9" => "£�"
        ),
        array(
            "0" => "",
            "1" => "Luxlo",
            "2" => "Property",
            "3" => "Amit",
            "4" => "Chadha",
            "5" => "www.amayadesign.co.uk/AmitChadha",
            "6" => "www.amayadesign.co.uk/",
            "7" => "AmitChadha",
            "8" => "Y",
            "9" => "£�"
        ),
        array(
            "0" => "",
            "1" => "タマ",
            "2" => "いぬ",
            "3" => "",
            "4" => "",
            "5" => "",
            "6" => "",
            "7" => "",
            "8" => "",
            "9" => "£�"
        ),
        array(
            "0" => "",
            "1" => "Finchatton",
            "2" => "",
            "3" => "Adam",
            "4" => "Hunter",
            "5" => "www.amayadesign.co.uk/AdamHunter",
            "6" => "www.amayadesign.co.uk/",
            "7" => "AdamHunter",
            "8" => "Y",
            "9" => "£�"
        )
    );


    function __construct(){
        if (!$this->databaseExists())
        {
            \DB::create(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__, __TEST_DB_SCHEMA__);
        }

        $this->connection = new \DB(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__,__TEST_DB_SCHEMA__);
        $this->table = new \Table($this->connection, $this->tableName);

        if (!$this->tableExists())
        {
            $this->createTable();
        }

        $this->table->delete();
        foreach ($this->data as $row)
        {
            $this->table->insert($row);
        }
    }

    private function createReader(){
        return Core::getCodeCoverageWrapper("SqlRandomReader", array(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__, __TEST_DB_SCHEMA__, $this->tableName));
    }

    private function databaseExists() {
        $existingDatabases = \DB::getAvailable(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__);
        $databaseExists = in_array(__TEST_DB_SCHEMA__, $existingDatabases );
        return $databaseExists;
    }

    private function tableExists()
    {
        $existingTables = \Table::getAvailable($this->connection);
        $tableExists = in_array($this->tableName, $existingTables);
        return $tableExists;
    }

    private function getColumnsFromData($data) {
        $columns = array();
        foreach ($data[0] as $columnName => $value){
            $columns[$columnName] = $this->defaultDataType;
        }

        return $columns;
    }

    private function createTable() {
        \Table::create($this->connection, $this->tableName, $this->getColumnsFromData($this->data));
    }

    private function compareRowAgainstDB ($reader, $rowIndex) {
        $expected = $this->data[$rowIndex];

        $current = $reader->readRow($rowIndex);

        Assert::areIdentical($expected, $current);
    }

    function testGetRowCount() {
        $reader = $this->createReader();

        Assert::areIdentical(4, $reader->getRowCount());
    }

    function testReadAllRows() {
        $reader = $this->createReader();

        for ($rowIndex = 0; $rowIndex < count($this->data); $rowIndex++)
        {
            $this->compareRowAgainstDB($reader, $rowIndex);
        }
    }
}