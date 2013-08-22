<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/RowListeners/HashReaderGroupsExportingRowListener.php");
include_once("mocks/MockRamWriterFactory.php");

include_once(__ROOT_DIR__ . "src/Writers/RamWriter.php");
include_once(__ROOT_DIR__ . "src/RandomReaders/RamRandomReader.php");
class TestHashReaderGroupsExportingRowListener extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createRamReader($ramId, $readerData){
        unset($GLOBALS[$ramId]);

        $writer = new \RamWriter($ramId);
        foreach ($readerData as $row){
            $writer->writeRow($row);
        }

        $reader = new \RamRandomReader($ramId);
        return $reader;
    }

    private function createListener(\WriterFactory $factory){
        return Core::getCodeCoverageWrapper("HashReaderGroupsExportingRowListener", array($factory));
    }

    function testSameGroupIfSameHashAndReader(){
        $factory = new MockRamWriterFactory();
        $listener = $this->createListener($factory);

        Assert::areIdentical(0, count($factory->createdWriters));

        $reader1 = $this->createRamReader("reader1", array(
            array("column1" => "value1"),
            array("column1" => "value2"),
        ));
        $hash1 = "hash1";

        $listener->receiveRow($reader1, 0, $hash1);
        Assert::areIdentical(1, count($factory->createdWriters));

        $listener->receiveRow($reader1, 1, $hash1);
        Assert::areIdentical(1, count($factory->createdWriters));
    }

    function testNewGroupIfSameReaderButDifferentHash(){
        $factory = new MockRamWriterFactory();
        $listener = $this->createListener($factory);

        Assert::areIdentical(0, count($factory->createdWriters));

        $reader1 = $this->createRamReader("reader1", array(
            array("column1" => "value1"),
            array("column1" => "value2"),
        ));
        $hash1 = "hash1";

        $listener->receiveRow($reader1, 0, $hash1);
        Assert::areIdentical(1, count($factory->createdWriters));

        $hash2 = "hash2";

        $listener->receiveRow($reader1, 1, $hash2);
        Assert::areIdentical(2, count($factory->createdWriters));
    }

    function testNewGroupIfSameHashButDifferentReader(){
        $factory = new MockRamWriterFactory();
        $listener = $this->createListener($factory);

        Assert::areIdentical(0, count($factory->createdWriters));

        $reader1 = $this->createRamReader("reader1", array(
            array("column1" => "value1"),
            array("column1" => "value2"),
        ));
        $hash1 = "hash1";

        $listener->receiveRow($reader1, 0, $hash1);
        Assert::areIdentical(1, count($factory->createdWriters));

        $reader2 = $this->createRamReader("reader2", array(
            array("column1" => "value1"),
            array("column1" => "value2"),
        ));

        $listener->receiveRow($reader2, 1, $hash1);
        Assert::areIdentical(2, count($factory->createdWriters));
    }
}