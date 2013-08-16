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

    private $createTestFilePath = "sampleFiles/test_csv_writer_create.csv";
    function testCreateFile(){
        $this->deleteFile($this->createTestFilePath);

        $this->createWriter($this->createTestFilePath);

        Assert::isTrue(file_exists($this->createTestFilePath));
    }

    function testCreatingANotValidPathThrowsAWriterExceptionWithCode200(){
        $path = "";

        $exceptionThrown = false;
        try{
            $this->createWriter($path);
        }catch (\WriterException $e){
            $exceptionThrown = true;
            Assert::areIdentical(200, $e->getCode());
        }

        Assert::isTrue($exceptionThrown);
    }

    private $writeTestFilePath = "sampleFiles/test_csv_writer_write.csv";
    function testWritingRow(){
        $this->deleteFile($this->writeTestFilePath);

        $writer = $this->createWriter($this->writeTestFilePath);

        $inputRow = array(
            "0" => "Foo",
            "1" => "Bar"
        );
        $writer->writeRow($inputRow);

        $reader = new \CsvRandomReader($this->writeTestFilePath);
        $outputRow = $reader->readRow(0);

        Assert::areIdentical($inputRow, $outputRow);
    }

    function testColumnNamesNotWriting(){
        $this->deleteFile($this->writeTestFilePath);

        $writer = $this->createWriter($this->writeTestFilePath);

        $inputRow = array(
            "0" => "Foo",
            "1" => "Bar",
            "Foo" => "Hi"
        );
        $writer->writeRow($inputRow);

        $reader = new \CsvRandomReader($this->writeTestFilePath);
        $outputRow = $reader->readRow(0);

        $expected = array(
            "0" => "Foo",
            "1" => "Bar",
            "2" => "Hi"
        );

        Assert::areIdentical($expected, $outputRow);
    }

    function testWritingUTF8Characters(){
        $this->deleteFile($this->writeTestFilePath);

        $writer = $this->createWriter($this->writeTestFilePath);

        $inputRow = array(
            "0" => "₤☻£﷼"
        );
        $writer->writeRow($inputRow);

        $reader = new \CsvRandomReader($this->writeTestFilePath);
        $outputRow = $reader->readRow(0);

        $expected = array(
            "0" => "₤☻£﷼"
        );

        Assert::areIdentical($expected, $outputRow);
    }

    private $appendTestFilePath = "sampleFiles/test_csv_writer_append.csv";
    function testAppending(){
        $this->deleteFile($this->appendTestFilePath);

        $writer = $this->createWriter($this->appendTestFilePath);
        $inputRow1 = array(
            "0" => "Foo",
            "1" => "Bar"
        );
        $writer->writeRow($inputRow1);

        $writer2 = $this->createWriter($this->appendTestFilePath);
        $inputRow2 = array(
            "0" => "Bar",
            "1" => "Foo"
        );
        $writer2->writeRow($inputRow2);

        $reader = new \CsvRandomReader($this->appendTestFilePath);
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

    function testWritingNotSortedRowsShouldWriteThemUnsorted(){
        $this->deleteFile($this->writeTestFilePath);

        $writer = $this->createWriter($this->writeTestFilePath);

        $writer->writeRow(array(
            "2" => "Foo",
            "0" => "Hi",
            "1" => "Bar"
        ));

        $reader = new \CsvRandomReader($this->writeTestFilePath);

        $outputRow2 = $reader->readRow(0);

        $expected = array(
            "0" => "Foo",
            "1" => "Hi",
            "2" => "Bar"
        );

        Assert::areIdentical($expected, $outputRow2);
    }

    function testWritingRowWithoutColumnNames(){
        $this->deleteFile($this->writeTestFilePath);

        $writer = $this->createWriter($this->writeTestFilePath);

        $writer->writeRow(array(
            "Foo",
            "Hi",
            "Bar"
        ));

        $reader = new \CsvRandomReader($this->writeTestFilePath);

        $outputRow2 = $reader->readRow(0);

        $expected = array(
            "0" => "Foo",
            "1" => "Hi",
            "2" => "Bar"
        );

        Assert::areIdentical($expected, $outputRow2);
    }
}