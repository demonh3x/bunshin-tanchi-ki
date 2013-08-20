<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/RandomReaders/FilteringRandomReader.php");

include_once(__ROOT_DIR__ . "src/Writers/RamWriter.php");
include_once(__ROOT_DIR__ . "src/RandomReaders/RamRandomReader.php");
class TestFilteringRandomReader extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
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

    private function createReader($data){
        $ramReader = $this->createRamReader("TestFilteringRandomReader", $data);
        return new \FilteringRandomReader($ramReader, new MockRowFilter());
    }

    function testFilteringReaderShouldFilterTheReadedRow(){
        $row = array(
            "column" => "value"
        );
        $reader = $this->createReader(array($row));

        $filteredRow = $reader->readRow(0);
        Assert::isTrue(isset($filteredRow[FLAG_INDEX]));
    }
}

define("FLAG_INDEX", "filteredRow");
class MockRowFilter implements \RowFilter {
    function applyTo($row) {
        $row[FLAG_INDEX] = "";
        return $row;
    }
}