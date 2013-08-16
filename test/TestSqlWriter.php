<?php
namespace Enhance;

include_once("../src/SQL/DB.php");
include_once("../src/SQL/SQL.php");
include_once("../src/SQL/Table.php");
include_once("../src/Writers/SqlWriter.php");
include_once("../src/RandomReaders/SqlRandomReader.php");

class TestSqlWriter extends TestFixture{
    /*private function createWriter($path){
        return Core::getCodeCoverageWrapper("CsvWriter", array($path));
    }*/

    private $connection;

    function __construct(){
        $this->schemaLowercase = strtolower(__TEST_DB_SCHEMA__);
        $this->createDatabaseIfNotExists();
        $this->connection = $this->createTestConnection();
    }

    public function setUp(){
        $this->createDatabaseIfNotExists();
    }

    private function createDatabaseIfNotExists() {
        $existingDatabases = \DB::getAvailable(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__);
        foreach ($existingDatabases as $index => $database)
        {
            $existingDatabases[$index] = strtolower($existingDatabases[$index]);
        }
        if (!in_array($this->schemaLowercase, $existingDatabases ))
        {
            \DB::create(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__, $this->schemaLowercase);
        }
    }

    private function createTestConnection(){
        return new \DB(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__,$this->schemaLowercase);
    }

    private function tableExists($tableName)
    {
        $existingTables = \Table::getAvailable($this->connection);
        foreach ($existingTables as $index => $table)
        {
            $existingTables[$index] = strtolower($existingTables[$index]);
        }

        $tableExists = in_array($tableName, $existingTables);

        return $tableExists;
    }

    private function deleteTableContentIfExists($tableExists, $tableName) {
        if ($tableExists)
        {
            $this->connection->query(\SQL::delete($tableName, null));
        }
    }

    private function readAllRows($reader)
    {
        $totalRows = $reader->getRowCount();
        $outputRows = array();
        for ($i = 0; $i < $totalRows; $i++)
        {
            $outputRows[$i] = $reader->readRow($i);
        }

        return $outputRows;
    }

    function testWritingRow(){

        $tableName = strtolower("testWritingRow");

        $expected = array (
            array (
                "id" => "1",
                "name" => "1",
                "address" => "1",
                "country" => "1"
            ),

            array (
                "id" => "2",
                "name" => "2",
                "address" => "2",
                "country" => "2"
            )
        );

        $tableExists = $this->tableExists($tableName);
        $this->deleteTableContentIfExists($tableExists, $tableName);

        $writer = new \SqlWriter(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__, $this->schemaLowercase, $tableName);

        foreach ($expected as $row)
        {
            $writer->writeRow($row);
        }

        $reader = new \SqlRandomReader(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__, $this->schemaLowercase, $tableName);
        $outputRows = $this->readAllRows($reader);

        Assert::areIdentical($expected, $outputRows);
    }

    function testAddingDataWithANonExistingColumn() {
        $tableName = strtolower("testAddingDataWithANonExistingColumn");

        $tableExists = $this->tableExists($tableName);
        $this->deleteTableContentIfExists($tableExists, $tableName);

        $writer = new \SqlWriter(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__, $this->schemaLowercase, $tableName);

        $firstInput = array ("name" => "ADRIAN", "surname" => "GONZALEZ");
        $secondInput = array ("name" => "ADRIAN", "surname" => "GONZALEZ", "city" => "VILAREAL", "age" => "20");

        $writer->writeRow($firstInput);
        $writer->writeRow($secondInput);

        $expected = array (
            array (
                "name" => "ADRIAN",
                "surname" => "GONZALEZ",
                "city" => null,
                "age" => null
            ),
            array (
                "name" => "ADRIAN",
                "surname" => "GONZALEZ",
                "city" => "VILAREAL",
                "age" => "20"
            )
        );

        $reader = new \SqlRandomReader(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__, $this->schemaLowercase, $tableName);
        $outputRows = $this->readAllRows($reader);

        Assert::areIdentical($expected, $outputRows);
    }

    function testAddingNonExistingTable() {

        $tableName = strtolower("testAddingNonExistingTable");

        $tableExists = $this->tableExists($tableName);
        $this->deleteTableContentIfExists($tableExists, $tableName);

        $writer = new \SqlWriter(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__, $this->schemaLowercase, $tableName);

        $firstInput = array ("name" => "ADRIAN", "surname" => "GONZALEZ");

        $writer->writeRow($firstInput);

        $tableExists = $this->tableExists($tableName);
        Assert::areIdentical(true, $tableExists);
    }
}
