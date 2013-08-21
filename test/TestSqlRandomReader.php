<?php
namespace Enhance;

include_once("../src/SQL/DB.php");
include_once("../src/SQL/SQL.php");
include_once("../src/SQL/Table.php");
include_once("../src/RandomReaders/SqlRandomReader.php");

class TestSqlRandomReader extends TestFixture{
    private $connection;
    private $defaultDataType = "varchar(100)";
    private $tableName = "testSqlRandomReader";

    function __construct(){
        if (!$this->databaseExists())
        {
            $this->createDatabase();
        }
        $this->connection = $this->createTestConnection();

        if (!$this->tableExists())
        {
            $this->createTable();
        }

        $this->deleteTableContent($this->tableName);
        $this->addContentToTable($this->data());
    }

    private function createReader(){
        return Core::getCodeCoverageWrapper("SqlRandomReader", array(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__, __TEST_DB_SCHEMA__, $this->tableName));
    }

    private function databaseExists() {
        $existingDatabases = \DB::getAvailable(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__);
        $databaseExists = in_array(__TEST_DB_SCHEMA__, $existingDatabases );
        return $databaseExists;
    }

    private function createDatabase() {
        \DB::create(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__, __TEST_DB_SCHEMA__);
    }

    private function createTestConnection(){
        return new \DB(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__,__TEST_DB_SCHEMA__);
    }

    private function tableExists()
    {
        $existingTables = \Table::getAvailable($this->connection);
        $tableExists = in_array($this->tableName, $existingTables);
        return $tableExists;
    }

    private function deleteTableContent() {
        $this->connection->query(\SQL::delete($this->tableName, null));
    }

    private function addContentToTable($data) {
        $table = new \Table($this->connection, $this->tableName);
        foreach ($data as $row)
        {
            $table->insert($row);
        }
    }

    private function data() {
        $data = array (
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

        return $data;
    }

    private function getColumnsFromData($data) {
        $columns = array();
        foreach ($data[0] as $columnName => $value){
            $columns[$columnName] = $this->defaultDataType;
        }

        return $columns;
    }

    private function createTable() {
        \Table::create($this->connection, $this->tableName, $this->getColumnsFromData($this->data()));
    }

    function testReadFirstRow(){
        $reader = $this->createReader();

        $expected = array(
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
        );

        $current = $reader->readRow(0);

        Assert::areIdentical($expected, $current);
    }

    function testReadThirdRow(){
        $reader = $this->createReader();

        $expected = array(
            "0" => "", "1" => "タマ", "2" => "いぬ", "3" => "", "4" => "",
            "5" => "", "6" => "",
            "7" => "", "8" => "", "9" => "£�"
        );
        $current = $reader->readRow(2);

        Assert::areIdentical($expected, $current);
    }

    function testJumpingForthToSecondRow(){
        $reader = $this->createReader();

        $expected = array(
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
        );
        $current = $reader->readRow(3);

        Assert::areIdentical($expected, $current);

        $expected = array(
            "0" => "", "1" => "Luxlo", "2" => "Property", "3" => "Amit", "4" => "Chadha",
            "5" => "www.amayadesign.co.uk/AmitChadha", "6" => "www.amayadesign.co.uk/",
            "7" => "AmitChadha", "8" => "Y", "9" => "£�"
        );
        $current = $reader->readRow(1);

        Assert::areIdentical($expected, $current);
    }

    function testGetRowCount() {
        $reader = $this->createReader();

        Assert::areIdentical(4, $reader->getRowCount());
    }
}