<?php
namespace Enhance;

include_once("../src/SQL/DB.php");
include_once("../src/SQL/SQL.php");
include_once("../src/SQL/Table.php");
include_once("../src/Writers/SqlWriter.php");

class TestSqlWriter extends TestFixture{

    private $connection;

    function __construct(){
        if (!$this->databaseExists())
        {
            \DB::create(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__, __TEST_DB_SCHEMA__);
        }
        $this->connection = new \DB(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__,__TEST_DB_SCHEMA__);
    }

    private function databaseExists() {
        $existingDatabases = \DB::getAvailable(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__);
        $databaseExists = in_array(__TEST_DB_SCHEMA__, $existingDatabases );
        return $databaseExists;
    }

    private function createWriter($ip, $user, $pass, $schema, $table){
        $args = array ($ip, $user, $pass, $schema, $table);
        return Core::getCodeCoverageWrapper("SqlWriter", $args);
    }

    private function tableExists($tableName)
    {
        $existingTables = \Table::getAvailable($this->connection);
        $tableExists = in_array($tableName, $existingTables);
        return $tableExists;
    }

    private function readAllRows($table)
    {
        $outputRows = $table->search();

        return $outputRows;
    }

    private function writeRows ($inputRows, $tableName)
    {
        $writer = $this->createWriter(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__, __TEST_DB_SCHEMA__, $tableName);

        foreach ($inputRows as $row)
        {
            $writer->writeRow($row);
        }
    }

    private function compareRowsAgainstDB ($expected, $table) {
        $outputRows = $this->readAllRows($table);

        Assert::areIdentical($expected, $outputRows);
    }

    function testAddingNonExistingTable() {

        $tableName = "testAddingNonExistingTable";
        $table = new \Table($this->connection, $tableName);

        $inputRow = array (
            array(
                "name" => "ADRIAN",
                "surname" => "GONZALEZ"
            )
        );

        $table->drop();

        $tableExists = $this->tableExists($tableName);
        Assert::areIdentical(false, $tableExists);

        $this->writeRows($inputRow, $tableName);
        $tableExists = $this->tableExists($tableName);
        Assert::areIdentical(true, $tableExists);
    }

    function testWritingRow(){

        $tableName = "testWritingRow";
        $table = new \Table($this->connection, $tableName);

        $inputRows = array (
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

        $table->delete();
        $this->writeRows($inputRows, $tableName);
        $this->compareRowsAgainstDB($inputRows, $table);
    }

    function testAddingDataWithANonExistingColumn() {

        $tableName = "testAddingDataWithANonExistingColumn";
        $table = new \Table($this->connection, $tableName);

        $inputRows = array (
            array (
                "name" => "ADRIAN",
                "surname" => "GONZALEZ"
            ),
            array (
                "name" => "ADRIAN",
                "surname" => "GONZALEZ",
                "city" => "VILAREAL",
                "age" => "20"
            )
        );

        $table->delete();
        $this->writeRows($inputRows, $tableName);

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

        $this->compareRowsAgainstDB($expected, $table);
    }
}
