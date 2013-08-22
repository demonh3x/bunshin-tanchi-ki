<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Writers/FilteringWriter.php");
include_once(__ROOT_DIR__ . "src/Writers/RamWriter.php");
include_once(__ROOT_DIR__ . "src/RandomReaders/RamRandomReader.php");
include_once("mocks/MockRowFilter.php");
class TestFilteringWriter extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    function testFiltering(){
        $ramId = "testFiltering";
        $ramWriter = new \RamWriter($ramId);
        $writer = Core::getCodeCoverageWrapper("FilteringWriter", array($ramWriter, new MockRowFilter()));

        $writer->writeRow(array("column"=>"value"));

        $reader = new \RamRandomReader($ramId);
        Assert::isTrue(MockRowFilter::hasBeenFiltered($reader->readRow(0)));
    }
}