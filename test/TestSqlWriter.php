<?php
namespace Enhance;

include_once("../src/SQL/DB.php");
include_once("../src/SQL/SQL.php");
include_once("../src/SQL/Table.php");
include_once("../src/Writers/SqlWriter.php");

class TestSqlWriter extends TestFixture{

    private $connection;
    private $table;

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

    private function createTableConnection($tableName) {
        $this->table = new \Table($this->connection, $tableName);
    }

    private function tableExists($tableName)
    {
        $existingTables = \Table::getAvailable($this->connection);
        $tableExists = in_array($tableName, $existingTables);
        return $tableExists;
    }

    private function readAllRows()
    {
        $outputRows = $this->table->search();

        return $outputRows;
    }

    private function emptyTable($tableName)
    {
        if ($this->tableExists($tableName))
        {
            $this->table->delete();
        }
    }

    private function dropTable($tableName)
    {
        if ($this->tableExists($tableName))
        {
            $this->table->drop();
        }
    }

    private function writeRows ($inputRows, $tableName)
    {
        $writer = $this->createWriter(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__, __TEST_DB_SCHEMA__, $tableName);

        foreach ($inputRows as $row)
        {
            $writer->writeRow($row);
        }
    }

    private function compareRowsAgainstDB ($expected, $tableName) {
        $outputRows = $this->readAllRows($tableName);

        Assert::areIdentical($expected, $outputRows);
    }

    function testWritingRow(){

        $tableName = "testWritingRow";
        $this->table = new \Table($this->connection, $tableName);

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

        $this->emptyTable($tableName);
        $this->writeRows($inputRows, $tableName);
        $this->compareRowsAgainstDB($inputRows, $tableName);
    }

    function testAddingDataWithANonExistingColumn() {

        $tableName = "testAddingDataWithANonExistingColumn";
        $this->table = new \Table($this->connection, $tableName);

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

        $this->emptyTable($tableName);
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

        $this->compareRowsAgainstDB($expected, $tableName);
    }

    function testAddingNonExistingTable() {

        $tableName = "testAddingNonExistingTable";
        $this->table = new \Table($this->connection, $tableName);

        $inputRow = array (
            array(
                "name" => "ADRIAN",
                "surname" => "GONZALEZ"
            )
        );

        $this->dropTable($tableName);

        $tableExists = $this->tableExists($tableName);
        Assert::areIdentical(false, $tableExists);

        $this->writeRows($inputRow, $tableName);
        $tableExists = $this->tableExists($tableName);
        Assert::areIdentical(true, $tableExists);
    }
}
