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

    function testWritingRow(){

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

        $connection = new \DB("localhost", "root", "root", "sqlreader");

        $tableExists = in_array("testWritingRow", \Table::getAvailable($connection));

        if ($tableExists)
        {
            $connection->query(\SQL::delete("testWritingRow", null));
        }
        else
        {
            $query = \SQL::createTable("testWritingRow", $expected);
            $connection->query($query);
        }

        $writer = new \SqlWriter("localhost", "root", "root", "sqlreader", "testWritingRow");

        foreach ($expected as $row)
        {
            $writer->writeRow($row);
        }

        $reader = new \SqlRandomReader("localhost", "root", "root", "sqlreader", "testWritingRow");

        $totalRows = $reader->getRowCount();
        $outputRows = array();
        for ($i = 0; $i < $totalRows; $i++)
        {
            $outputRows[$i] = $reader->readRow($i);
        }

        Assert::areIdentical($expected, $outputRows);
    }

    function testAddingDataWithANonExistingColumn() {

        $connection = new \DB("localhost", "root", "root", "sqlreader");

        $tableName = "testAddingDataWithANonExistingColumn";

        $tableExists = in_array($tableName, \Table::getAvailable($connection));

        if ($tableExists)
        {
            $connection->query(\SQL::delete($tableName, null));
        }

        $writer = new \SqlWriter("localhost", "root", "root", "sqlreader", $tableName);

        $firstInput = array ("name" => "ADRIAN", "surname" => "GONZALEZ");
        $secondInput = array ("name" => "ADRIAN", "surname" => "GONZALEZ", "city" => "VILAREAL", "age" => "20");

        $writer->writeRow($firstInput);
        $writer->writeRow($secondInput);

        $expected = array (
            array (
                "city" => null,
                "name" => "ADRIAN",
                "surname" => "GONZALEZ",
                "age" => null
            ),
            array (
                "city" => "VILAREAL",
                "name" => "ADRIAN",
                "surname" => "GONZALEZ",
                "age" => "20"
            )
        );

        $reader = new \SqlRandomReader("localhost", "root", "root", "sqlreader", $tableName);

        $totalRows = $reader->getRowCount();
        $outputRows = array();
        for ($i = 0; $i < $totalRows; $i++)
        {
            $outputRows[$i] = $reader->readRow($i);
        }

        Assert::areIdentical($expected, $outputRows);
    }
}
