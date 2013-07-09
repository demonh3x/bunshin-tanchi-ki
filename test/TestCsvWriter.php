<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Writers/CsvWriter.php");
include_once(__ROOT_DIR__ . "src/Readers/CsvReader.php");

class TestCsvWriter extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createWriter(){
        return Core::getCodeCoverageWrapper("CsvWriter");
    }

    private function deleteFile($path){
        if (file_exists($path)){
            unlink($path);
            if (file_exists($path)){
                throw new \Exception("Cannot delete the file: [$path]");
            }
        }
    }

    function testCreateFile(){
        $writer = $this->createWriter();

        $path = "sampleFiles/test_csv_writer_create.csv";
        $this->deleteFile($path);

        $writer->create($path);
        Assert::isTrue(file_exists($path));

        $writer->__destruct();

        $this->deleteFile($path);
    }

    function testWritingRow(){
        $writer = $this->createWriter();

        $path = "sampleFiles/test_csv_writer_write.csv";
        $this->deleteFile($path);

        $writer->create($path);
        $inputRow = array(
            "0" => "Foo",
            "1" => "Bar"
        );
        $writer->writeRow($inputRow);

        $reader = new \CsvReader();
        $reader->open($path);
        $outputRow = $reader->readRow();

        Assert::areIdentical($inputRow, $outputRow);

        $this->deleteFile($path);
    }

    function testIsReady(){
        $writer = $this->createWriter();
        Assert::isFalse($writer->isReady());

        $path = "sampleFiles/test_csv_writer_ready.csv";
        $this->deleteFile($path);

        $writer->create($path);
        Assert::isTrue($writer->isReady());

        $this->deleteFile($path);
    }
}