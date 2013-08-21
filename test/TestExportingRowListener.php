<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/RowListeners/ExportingRowListener.php");
include_once(__ROOT_DIR__ . "src/Writers/RamWriter.php");
include_once(__ROOT_DIR__ . "src/RandomReaders/RamRandomReader.php");

class TestExportingRowListener extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createRowListener(\Writer $writer){
        return Core::getCodeCoverageWrapper("ExportingRowListener", array($writer));
    }

    private function createReaderWithData($ramId, $data = array()){
        $writer = new \RamWriter($ramId);
        foreach ($data as $row){
            $writer->writeRow($row);
        }

        return new \RamRandomReader($ramId);
    }

    function testWritingRow(){
        $ramId = "testWritingRow";

        $outputWriter = new \RamWriter($ramId);
        $listener = $this->createRowListener($outputWriter);
        $assertingReader = new \RamRandomReader($ramId);

        $data = array(
            array("Column" => "value")
        );
        $inputDataReader = $this->createReaderWithData(
            "testWritingRowInputData",
            $data
        );
        $listener->receiveRow($inputDataReader, 0, "");

        Assert::areIdentical($data[0], $assertingReader->readRow(0));
    }
}