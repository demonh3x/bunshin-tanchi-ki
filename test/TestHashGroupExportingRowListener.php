<?php

namespace Enhance;

include_once(__ROOT_DIR__ . "src/RowListeners/HashGroupsExportingRowListener.php");
include_once(__ROOT_DIR__ . "src/Writers/RamWriter.php");
include_once(__ROOT_DIR__ . "src/RandomReaders/RamRandomReader.php");

include_once("mocks/MockRamWriterFactory.php");

class TestHashGroupExportingRowListener extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createReaderWithData($ramId, $data = array()){
        $writer = new \RamWriter($ramId);
        foreach ($data as $row){
            $writer->writeRow($row);
        }

        return new \RamRandomReader($ramId);
    }

    private function createListener(\WriterFactory $factory){
        return Core::getCodeCoverageWrapper("HashGroupsExportingRowListener", array($factory));
    }

    public function testExporting(){
        $factory = new MockRamWriterFactory();
        $listener = $this->createListener($factory);

        $data = array(
            array("Column1" => "value1"),
            array("Column1" => "value2"),
            array("Column1" => "value3"),
        );
        $reader = $this->createReaderWithData("testExporting", $data);

        Assert::areIdentical(0, count($factory->createdWriters));

        $listener->receiveRow($reader, 0, "hash1");
        Assert::areIdentical(1, count($factory->createdWriters));
        $readerForCreatedWriter = new \RamRandomReader($factory->getRamId("hash1"));
        Assert::areIdentical($data[0], $readerForCreatedWriter->readRow(0));

        $listener->receiveRow($reader, 1, "hash2");
        Assert::areIdentical(2, count($factory->createdWriters));
        $readerForCreatedWriter = new \RamRandomReader($factory->getRamId("hash2"));
        Assert::areIdentical($data[1], $readerForCreatedWriter->readRow(0));
    }
}