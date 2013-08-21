<?php
namespace Enhance;

include_once("../src/SQL/DB.php");
include_once("../src/SQL/SQL.php");
include_once("../src/SQL/Table.php");
include_once("../src/Writers/SqlWriter.php");

class TestSqlWriter extends TestFixture{
    /*private function createWriter($path){
        return Core::getCodeCoverageWrapper("CsvWriter", array($path));
    }*/

    private $connection;

    function __construct(){
        $this->createDatabaseIfNotExists();
        $this->connection = $this->createTestConnection();
    }

    public function setUp(){
        $this->createDatabaseIfNotExists();
    }

    private function createDatabaseIfNotExists() {
        $existingDatabases = \DB::getAvailable(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__);

        if (!in_array(__TEST_DB_SCHEMA__, $existingDatabases ))
        {
            \DB::create(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__, __TEST_DB_SCHEMA__);
        }
    }

    private function createWriter($ip, $user, $pass, $schema, $table){
        $args = array ($ip, $user, $pass, $schema, $table);
        return Core::getCodeCoverageWrapper("SqlWriter", $args);
    }

    private function createTestConnection(){
        return new \DB(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__,__TEST_DB_SCHEMA__);
    }

    private function tableExists($tableName)
    {
        $existingTables = \Table::getAvailable($this->connection);

        $tableExists = in_array($tableName, $existingTables);

        return $tableExists;
    }

    private function deleteTableContentIfExists($tableName) {
        $tableExists = $this->tableExists($tableName);
        if ($tableExists)
        {
            $this->connection->query(\SQL::delete($tableName, null));
        }
    }

    private function readAllRows($tableName)
    {
        $query = \SQL::select($tableName);
        $outputRows = $this->connection->query($query);

        return $outputRows;
    }

    function testWritingRow(){

        $tableName = "testWritingRow";
        $this->deleteTableContentIfExists($tableName);

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


        $writer = $this->createWriter(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__, __TEST_DB_SCHEMA__, $tableName);

        foreach ($expected as $row)
        {
            $writer->writeRow($row);
        }

        $outputRows = $this->readAllRows($tableName);

        Assert::areIdentical($expected, $outputRows);
    }

    function testAddingDataWithANonExistingColumn() {

        $tableName = "testAddingDataWithANonExistingColumn";
        $this->deleteTableContentIfExists($tableName);

        $writer = $this->createWriter(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__, __TEST_DB_SCHEMA__, $tableName);

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


        $outputRows = $this->readAllRows($tableName);

        Assert::areIdentical($expected, $outputRows);
    }

    function testAddingNonExistingTable() {

        $tableName = "testAddingNonExistingTable";
        $this->deleteTableContentIfExists($tableName);

        $writer = $this->createWriter(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__, __TEST_DB_SCHEMA__, $tableName);

        $firstInput = array ("name" => "ADRIAN", "surname" => "GONZALEZ");

        $writer->writeRow($firstInput);

        $tableExists = $this->tableExists($tableName);
        Assert::areIdentical(true, $tableExists);
    }
}
