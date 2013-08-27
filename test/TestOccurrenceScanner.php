<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/OccurrenceScanner.php");

include_once(__ROOT_DIR__ . "src/Writers/RamWriter.php");
include_once(__ROOT_DIR__ . "src/RandomReaders/RamRandomReader.php");

include_once(__ROOT_DIR__ . "src/HashCalculators/PerColumnRowFilter.php");

include_once("mocks/LowercaseMockFilter.php");
include_once("mocks/MockRowListener.php");

class TestOccurrenceScanner extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createOccurrenceScanner($regex, $reader, $columns = array(), $rowFilter = null){
        return Core::getCodeCoverageWrapper("OccurrenceScanner", array($regex, $reader, $columns, $rowFilter));
    }

    private function createScanner($data, $regex, $columns = array(), $rowFilter = null){
        $reader = $this->createRamReader("testOccurrenceScannerDefaultRamReader", $data);
        $scanner = $this->createOccurrenceScanner($regex, array($reader), $columns, $rowFilter);

        return $scanner;
    }

    private function createRamReader($id, $data){
        unset($GLOBALS[$id]);
        $writer = new \RamWriter($id);

        foreach ($data as $row){
            $writer->writeRow($row);
        }

        $reader = new \RamRandomReader($id);

        return $reader;
    }

    private function getScannerOccurrences($scanner){
        $listener = new MockRowListener();
        $scanner->scan($listener);

        return  $listener->receivedData;
    }

    private function assertOccurrences($input, $expected, $regex, $columns = array(), $rowFilter = null){
        $scanner = $this->createScanner($input, $regex, $columns, $rowFilter);

        $outputArray = $this->getScannerOccurrences($scanner);

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

        $rowFilter = new \PerColumnRowFilter(array(
            "0" => new LowercaseMockFilter()
        ));

        $this->assertOccurrences($input, $expected, $regex, array(), $rowFilter);
    }

    private function assertNotMatching($input, $expected, $regex, $columns = array(), $rowFilter = null){
        $scanner = $this->createScanner($input, $regex, $columns, $rowFilter);

        $listener = new MockRowListener();
        $scanner->scan(new MockRowListener(), $listener);

        Assert::areIdentical($expected, $listener->receivedData);
    }

    function testReceivingNotMatching(){
        $input = array(
            array("0" => "Foo"),
            array("0" => "Bar")
        );
        $expected = array(
            array("0" => "Bar")
        );
        $regex = "/^Foo/";

        $this->assertNotMatching($input, $expected, $regex);
    }

    function testAllOccurrencesMultipleReaders(){
        $input1 = array(
            array("0" => "Foo1"),
            array("0" => "AFoo")
        );
        $reader1 = $this->createRamReader("testOccurrenceScannerMultipleReaders1", $input1);
        $input2 = array(
            array("0" => "Fo"),
            array("0" => "Foo2")
        );
        $reader2 = $this->createRamReader("testOccurrenceScannerMultipleReaders2", $input2);

        $regex = "/^Foo/";
        $scanner = $this->createOccurrenceScanner($regex, array($reader1, $reader2));

        $expected = array(
            array("0" => "Foo1"),
            array("0" => "Foo2")
        );
        Assert::areIdentical($expected, $this->getScannerOccurrences($scanner));
    }
}