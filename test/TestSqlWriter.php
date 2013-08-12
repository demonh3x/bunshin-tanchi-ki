<?php
namespace Enhance;

include_once("../src/SQL/DB.php");
include_once("../src/SQL/SQL.php");
include_once("../src/Writers/SqlWriter.php");
include_once("../src/RandomReaders/SqlRandomReader.php");

class TestSqlWriter extends TestFixture{
    /*private function createWriter($path){
        return Core::getCodeCoverageWrapper("CsvWriter", array($path));
    }*/

    function testWritingRow(){

        $writer = new \SqlWriter("localhost", "root", "root", "sqlreader", "testWritingRow");

        if ($writer->getTableExists())
        {
            $connection = new \DB("localhost", "root", "root", "sqlreader", "testWritingRow");
            $connection->query(\SQL::delete("testWritingRow", null));
        }


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

    function testAddingDataWithNonANonExistingColumn() {
        $writer = new \SqlWriter("localhost", "root", "root", "sqlreader", "testAddingDataWithNonANonExistingColumn");

        if ($writer->getTableExists())
        {
            $connection = new \DB("localhost", "root", "root", "sqlreader", "testAddingDataWithNonANonExistingColumn");
            $connection->query(\SQL::delete("testAddingDataWithNonANonExistingColumn", null));
        }

        $firstInput = array (
            "name" => "ADRIAN",
            "surname" => "GONZALEZ"
        );

        $secondInput = array (
            "name" => "ADRIAN",
            "surname" => "GONZALEZ",
            "city" => "VILAREAL"
        );

        $writer->writeRow($firstInput);
        $writer->writeRow($secondInput);

        $expected = array (
            array (
                "name" => "ADRIAN",
                "surname" => "GONZALEZ",
                "city" => "null"
            ),
            array (
                "name" => "ADRIAN",
                "surname" => "GONZALEZ",
                "city" => "VILAREAL"
            )
        );

        $reader = new \SqlRandomReader("localhost", "root", "root", "sqlreader", "testAddingDataWithNonANonExistingColumn");

        $totalRows = $reader->getRowCount();
        $outputRows = array();
        for ($i = 0; $i < $totalRows; $i++)
        {
            $outputRows[$i] = $reader->readRow($i);
        }

        Assert::areIdentical($expected, $outputRows);
    }

}
