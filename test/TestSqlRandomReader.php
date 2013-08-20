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
        $this->createDatabaseIfNotExists();
        $this->connection = $this->createTestConnection();
        $this->createTableIfNotExists();
    }

    public function setUp(){
        $this->createDatabaseIfNotExists();
        $this->createTableIfNotExists();
    }

    private function createDatabaseIfNotExists() {
        $existingDatabases = \DB::getAvailable(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__);

        if (!in_array(__TEST_DB_SCHEMA__, $existingDatabases ))
        {
            \DB::create(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__, __TEST_DB_SCHEMA__);
        }
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

        $table->insert($data);
    }

    private function createTableIfNotExists() {

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

            $columns = array();
            foreach ($data[0] as $columnName => $value){
                $columns[$columnName] = $this->defaultDataType;
            }

            $tableExists = $this->tableExists($this->tableName);
            if ($tableExists)
            {
                $this->deleteTableContent($this->tableName);
                foreach ($data as $row)
                {
                    $this->addContentToTable($row);
                }
            }
            else
            {
                $this->tableName = \Table::create($this->connection, $this->tableName, $columns);
            }
    }

    function testReadFirstRow(){
        $reader = new \SqlRandomReader(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__, __TEST_DB_SCHEMA__, $this->tableName);

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
        $reader = new \SqlRandomReader(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__, __TEST_DB_SCHEMA__, $this->tableName);

        $expected = array(
            "0" => "", "1" => "タマ", "2" => "いぬ", "3" => "", "4" => "",
            "5" => "", "6" => "",
            "7" => "", "8" => "", "9" => "£�"
        );
        $current = $reader->readRow(2);

        Assert::areIdentical($expected, $current);
    }

    function testJumpingForthToSecondRow(){
        $reader = new \SqlRandomReader(__TEST_DB_IP__, __TEST_DB_USER__, __TEST_DB_PASSWORD__, __TEST_DB_SCHEMA__, $this->tableName);

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
}