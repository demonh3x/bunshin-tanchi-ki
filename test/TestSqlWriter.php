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

        $connection = $this->createTestConnection();

        $tableExists = in_array($tableName, \Table::getAvailable($connection));

        if ($tableExists)
        {
            $connection->query(\SQL::delete($tableName, null));
        }
        else
        {
            $query = \SQL::createTable($tableName, $expected);
            $connection->query($query);
        }

        $writer = new \SqlWriter(TEST_DB_IP, TEST_DB_USER, TEST_DB_PASSWORD, TEST_DB_SCHEMA, $tableName);

        foreach ($expected as $row)
        {
            $writer->writeRow($row);
        }

        $reader = new \SqlRandomReader(TEST_DB_IP, TEST_DB_USER, TEST_DB_PASSWORD, TEST_DB_SCHEMA, $tableName);

        $totalRows = $reader->getRowCount();
        $outputRows = array();
        for ($i = 0; $i < $totalRows; $i++)
        {
            $outputRows[$i] = $reader->readRow($i);
        }

        Assert::areIdentical($expected, $outputRows);
    }

    function testAddingDataWithANonExistingColumn() {

        $connection = $this->createTestConnection();

        $tableName = "testAddingDataWithANonExistingColumn";

        $tableExists = in_array($tableName, \Table::getAvailable($connection));

        if ($tableExists)
        {
            $connection->query(\SQL::delete($tableName, null));
        }

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

        $totalRows = $reader->getRowCount();
        $outputRows = array();
        for ($i = 0; $i < $totalRows; $i++)
        {
            $outputRows[$i] = $reader->readRow($i);
        }

        Assert::areIdentical($expected, $outputRows);
    }

    function testAddingNonExistingTable() {

        $connection = $this->createTestConnection();

        $tableName = "testAddingNonExistingTable";

        $tableExists = in_array($tableName, \Table::getAvailable($connection));

        if ($tableExists)
        {
            $connection->query(\SQL::deleteTable($tableName));
        }

        $tableExists = in_array($tableName, \Table::getAvailable($connection));

        Assert::areIdentical($tableExists, false);

        $writer = new \SqlWriter(TEST_DB_IP, TEST_DB_USER, TEST_DB_PASSWORD, TEST_DB_SCHEMA, $tableName);

        $firstInput = array ("name" => "ADRIAN", "surname" => "GONZALEZ");

        $writer->writeRow($firstInput);

        $tableExists = in_array($tableName, \Table::getAvailable($connection));

        Assert::areIdentical($tableExists, true);
    }
}
