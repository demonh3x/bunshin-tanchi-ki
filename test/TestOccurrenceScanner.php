<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/OccurrenceScanner.php");

include_once(__ROOT_DIR__ . "src/Writers/RamWriter.php");
include_once(__ROOT_DIR__ . "src/RandomReaders/RamRandomReader.php");

include_once(__ROOT_DIR__ . "src/HashCalculators/RowFilter.php");
include_once("mocks/LowercaseMockFilter.php");

class TestOccurrenceScanner extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createScanner($data, $regex, $columns = array(), $rowFilter = null){
        $reader = $this->createRamReader("testOccurrenceScannerDefaultRamReader", $data);
        $scanner = new \OccurrenceScanner($reader, $regex, $columns, $rowFilter);

        return $scanner;
    }

    private function createRamReader($id, $data){
        $writer = new \RamWriter();

        unset($GLOBALS[$id]);
        $writer->create($id);

        foreach ($data as $row){
            $writer->writeRow($row);
        }

        $reader = new \RamRandomReader();
        $reader->open($id);

        return $reader;
    }

    function testGetResultsReturnsAnInstanceOfIterator(){
        $scanner = $this->createScanner(array(), "//");
        Assert::isTrue($scanner->getResults() instanceof \Iterator);
    }

    private function assertOccurrences($input, $expected, $regex, $columns = array(), $rowFilter = null){
        $scanner = $this->createScanner($input, $regex, $columns, $rowFilter);

        $output = $scanner->getResults();
        $outputArray = array();
        foreach ($output as $row){
            $outputArray[] = $row;
        }

        Assert::areIdentical($expected, $outputArray);
    }

    function testNoOccurrences(){
        $input = array(
            array("0" => "Hello!"),
            array("0" => "World!"),
        );
        $regex = "/^Foo/";
        $expected = array();

        $this->assertOccurrences($input, $expected, $regex);
    }

    function testAllOccurrences(){
        $input = array(
            array("0" => "Foo!"),
            array("0" => "Fooaaaa")
        );
        $regex = "/^Foo/";
        $expected = array(
            array("0" => "Foo!"),
            array("0" => "Fooaaaa")
        );

        $this->assertOccurrences($input, $expected, $regex);
    }

    function testTwoColumnsMatching(){
        $input = array(
            array("0" => "Foo!", "1" => "Foooooo"),
            array("0" => "Bar", "1" => "Bar")
        );
        $regex = "/^Foo/";
        $expected = array(
            array("0" => "Foo!", "1" => "Foooooo")
        );

        $this->assertOccurrences($input, $expected, $regex);
    }

    function testSearchInOneColumn(){
        $input = array(
            array("0" => "Foo", "1" => "Bar"),
            array("0" => "Bar", "1" => "Foo")
        );
        $expected = array(
            array("0" => "Foo", "1" => "Bar")
        );
        $regex = "/^Foo/";
        $columns = array("0");

        $this->assertOccurrences($input, $expected, $regex, $columns);
    }

    function testFilteredMatching(){
        $input = array(
            array("0" => "Foo"),
            array("0" => "Bar")
        );
        $regex = "/^foo/";
        $expected = array(
            array("0" => "Foo")
        );

        $rowFilter = new \RowFilter();
        $rowFilter->setGlobalFilter(new LowercaseMockFilter());

        $this->assertOccurrences($input, $expected, $regex, array(), $rowFilter);
    }
}