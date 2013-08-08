<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Writers/CsvWriter.php");
include_once(__ROOT_DIR__ . "src/RandomReaders/CsvRandomReader.php");

class TestCsvWriter extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createWriter($path){
        return Core::getCodeCoverageWrapper("CsvWriter", array($path));
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
        $path = "sampleFiles/test_csv_writer_create.csv";
        $this->deleteFile($path);

        $this->createWriter($path);

        Assert::isTrue(file_exists($path));
    }

    function testWritingRow(){
        $path = "sampleFiles/test_csv_writer_write.csv";
        $this->deleteFile($path);

        $writer = $this->createWriter($path);

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
        $path = "sampleFiles/test_csv_writer_write.csv";
        $this->deleteFile($path);

        $writer = $this->createWriter($path);

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
        $path = "sampleFiles/test_csv_writer_write.csv";
        $this->deleteFile($path);

        $writer = $this->createWriter($path);

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

    function testAppending(){
        $path = "sampleFiles/test_csv_writer_append.csv";
        $this->deleteFile($path);

        $writer = $this->createWriter($path);
        $inputRow1 = array(
            "0" => "Foo",
            "1" => "Bar"
        );
        $writer->writeRow($inputRow1);

        $writer2 = $this->createWriter($path);
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