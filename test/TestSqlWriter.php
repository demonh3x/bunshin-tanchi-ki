<?php
namespace Enhance;

include_once("../src/SQL/DB.php");
include_once("../src/SQL/SQL.php");
include_once("../src/SQL/Table.php");
include_once("../src/Writers/SqlWriter.php");
include_once("../src/RandomReaders/SqlRandomReader.php");

define("TEST_DB_IP", "localhost");
define("TEST_DB_USER", "root");
define("TEST_DB_PASSWORD", "root");
define("TEST_DB_SCHEMA", "sqlReaderTests");

class TestSqlWriter extends TestFixture{
    /*private function createWriter($path){
        return Core::getCodeCoverageWrapper("CsvWriter", array($path));
    }*/

    private $connection;
    function __construct(){
        $this->connection = $this->createTestConnection();
    }

    public function setUp(){
        $this->createDatabaseIfNotExists();
    }

    private function createDatabaseIfNotExists() {
        new \mysqli(TEST_DB_IP, TEST_DB_USER, TEST_DB_PASSWORD, TEST_DB_SCHEMA);

        if (mysqli_connect_errno())
        {
            $mysqli = new \mysqli(TEST_DB_IP, TEST_DB_USER, TEST_DB_PASSWORD);
            $mysqli->real_query(\SQL::createDatabase(TEST_DB_SCHEMA));
        }
    }

    private function createTestConnection(){
        return new \DB(TEST_DB_IP, TEST_DB_USER, TEST_DB_PASSWORD,TEST_DB_SCHEMA);
    }

    private function tableExists($tableName)
    {
        $tableExists = in_array($tableName, \Table::getAvailable($this->connection));

        return $tableExists;
    }

    private function deleteTableIfExists($tableExists, $tableName) {
        if ($tableExists)
        {
            $this->connection->query(\SQL::deleteTable($tableName));
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

        $tableName = "testWritingRow";

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
        $this->deleteTableIfExists($tableExists, $tableName);

        $writer = new \SqlWriter(TEST_DB_IP, TEST_DB_USER, TEST_DB_PASSWORD, TEST_DB_SCHEMA, $tableName);

        foreach ($expected as $row)
        {
            $writer->writeRow($row);
        }

        $reader = new \SqlRandomReader(TEST_DB_IP, TEST_DB_USER, TEST_DB_PASSWORD, TEST_DB_SCHEMA, $tableName);
        $outputRows = $this->readAllRows($reader);

        Assert::areIdentical($expected, $outputRows);
    }

    function testAddingDataWithANonExistingColumn() {
        $tableName = "testAddingDataWithANonExistingColumn";

        $tableExists = $this->tableExists($tableName);
        $this->deleteTableIfExists($tableExists, $tableName);

        $writer = new \SqlWriter(TEST_DB_IP, TEST_DB_USER, TEST_DB_PASSWORD, TEST_DB_SCHEMA, $tableName);

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

        $reader = new \SqlRandomReader(TEST_DB_IP, TEST_DB_USER, TEST_DB_PASSWORD, TEST_DB_SCHEMA, $tableName);
        $outputRows = $this->readAllRows($reader);

        Assert::areIdentical($expected, $outputRows);
    }

    function testAddingNonExistingTable() {

        $tableName = "testAddingNonExistingTable";

        $tableExists = $this->tableExists($tableName);
        $this->deleteTableIfExists($tableExists, $tableName);

        $tableExists = $this->tableExists($tableName);
        Assert::areIdentical(false, $tableExists);

        $writer = new \SqlWriter(TEST_DB_IP, TEST_DB_USER, TEST_DB_PASSWORD, TEST_DB_SCHEMA, $tableName);

        $tableExists = $this->tableExists($tableName);
        Assert::areIdentical(false, $tableExists);

        $firstInput = array ("name" => "ADRIAN", "surname" => "GONZALEZ");

        $writer->writeRow($firstInput);

        $tableExists = $this->tableExists($tableName);
        Assert::areIdentical(true, $tableExists);
    }
}
