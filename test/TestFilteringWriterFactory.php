<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Writers/FilteringWriterFactory.php");

include_once("mocks/MockRamWriterFactory.php");
include_once("mocks/MockRowFilter.php");
class TestFilteringWriterFactory extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    function testFiltering(){
        $mockFactory = new MockRamWriterFactory();
        $factory = new \FilteringWriterFactory(
            $mockFactory,
            new MockRowFilter()
        );

        $hash1 = "hash1";
        $writer = $factory->createWriter($hash1);
        $writer->writeRow(array());

        $reader = new \RamRandomReader($mockFactory->getRamId($hash1));

        Assert::isTrue(
            MockRowFilter::hasBeenFiltered($reader->readRow(0))
        );
    }


}