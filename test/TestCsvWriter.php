<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Writers/CsvWriter.php");
include_once(__ROOT_DIR__ . "src/RandomReaders/CsvRandomReader.php");

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

        $reader = new \CsvRandomReader($path);
        $outputRow = $reader->readRow(0);

        Assert::areIdentical($inputRow, $outputRow);
    }

    function testColumnNamesNotWriting(){
        $writer = $this->createWriter();

        $path = "sampleFiles/test_csv_writer_write.csv";
        $this->deleteFile($path);

        $writer->create($path);
        $inputRow = array(
            "0" => "Foo",
            "1" => "Bar",
            "Foo" => "Hi"
        );
        $writer->writeRow($inputRow);

        $reader = new \CsvRandomReader($path);
        $outputRow = $reader->readRow(0);

        $expected = array(
            "0" => "Foo",
            "1" => "Bar",
            "2" => "Hi"
        );

        Assert::areIdentical($expected, $outputRow);
    }

    function testWritingUTF8Characters(){
        $writer = $this->createWriter();

        $path = "sampleFiles/test_csv_writer_write.csv";
        $this->deleteFile($path);

        $writer->create($path);
        $inputRow = array(
            "0" => "₤☻£﷼"
        );
        $writer->writeRow($inputRow);

        $reader = new \CsvRandomReader($path);
        $outputRow = $reader->readRow(0);

        $expected = array(
            "0" => "₤☻£﷼"
        );

        Assert::areIdentical($expected, $outputRow);
    }

    function testIsReady(){
        $writer = $this->createWriter();
        Assert::isFalse($writer->isReady());

        $path = "sampleFiles/test_csv_writer_ready.csv";
        $this->deleteFile($path);

        $writer->create($path);
        Assert::isTrue($writer->isReady());
    }

    function testAppending(){
        $writer = $this->createWriter();

        $path = "sampleFiles/test_csv_writer_append.csv";
        $this->deleteFile($path);

        $writer->create($path);
        $inputRow1 = array(
            "0" => "Foo",
            "1" => "Bar"
        );
        $writer->writeRow($inputRow1);

        $writer2 = $this->createWriter();
        $writer2->create($path);
        $inputRow2 = array(
            "0" => "Bar",
            "1" => "Foo"
        );
        $writer2->writeRow($inputRow2);

        $reader = new \CsvRandomReader($path);
        $current = array();
        for ($i = 0; $i < $reader->getRowCount(); $i++){
            $current[] = $reader->readRow($i);
        }

        $expected = array(
            array(
                "0" => "Foo",
                "1" => "Bar"
            ),
            array(
                "0" => "Bar",
                "1" => "Foo"
            )
        );

        Assert::areIdentical($expected, $current);
    }
}