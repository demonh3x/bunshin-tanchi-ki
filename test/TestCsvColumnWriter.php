<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Writers/CsvColumnWriter.php");
include_once(__ROOT_DIR__ . "src/RandomReaders/CsvColumnRandomReader.php");
class TestCsvColumnWriter extends TestFixture{

    private $testFilePath = "sampleFiles/test_csv_column_writer.csv";

    private function createWriter(){
        return Core::getCodeCoverageWrapper("CsvColumnWriter", array($this->testFilePath));
    }

    private function deleteFile($path){
        if (file_exists($path)){
            unlink($path);
            if (file_exists($path)){
                throw new \Exception("Cannot delete the file: [$path]");
            }
        }
    }

    private function createReader(){
        return new \CsvColumnRandomReader($this->testFilePath);
    }

    private function readTestFileContent(){
        $reader = $this->createReader();
        $actualRows = array();
        for ($rowIndex = 0; $rowIndex < $reader->getRowCount(); $rowIndex++){
            $actualRows[] = $reader->readRow($rowIndex);
        }

        return $actualRows;
    }

    private function assertWritingMultipleRows($inputRows, $expectedRows){
        $writer = $this->createWriter();

        foreach ($inputRows as $row){
            $writer->writeRow($row);
        }

        $actualRows = $this->readTestFileContent();

        Assert::areIdentical($expectedRows, $actualRows);
    }


    public function setUp(){
        $this->deleteFile($this->testFilePath);
    }
    public function tearDown(){
        $this->deleteFile($this->testFilePath);
    }

    function testCreatingNonExistingFileWithoutWritingShouldCreateFile(){
        Assert::isFalse(file_exists($this->testFilePath));
        $this->createWriter();
        Assert::isTrue(file_exists($this->testFilePath));
    }

    function testWritingOneRowShouldSaveTheColumnNames(){
        $writer = $this->createWriter();

        $inputRow = array(
            "columnName1" => "value1",
            "columnName2" => "value2",
            "columnName3" => "value3"
        );
        $writer->writeRow($inputRow);

        $reader = $this->createReader();
        $actualRow = $reader->readRow(0);

        Assert::areIdentical($inputRow, $actualRow);
    }

    function testWritingThreeSortedRows(){
        $inputRows = array(
            array(
                "columnName1" => "value1A",
                "columnName2" => "value2A",
                "columnName3" => "value3A",
            ),
            array(
                "columnName1" => "value1B",
                "columnName2" => "value2B",
                "columnName3" => "value3B",
            ),
            array(
                "columnName1" => "value1C",
                "columnName2" => "value2C",
                "columnName3" => "value3C",
            ),
        );

        $this->assertWritingMultipleRows($inputRows, $inputRows);
    }

    function testWritingThreeUnsortedRows(){
        $inputRows = array(
            array(
                "columnName1" => "value1A",
                "columnName2" => "value2A",
                "columnName3" => "value3A",
            ),
            array(
                "columnName3" => "value3B",
                "columnName1" => "value1B",
                "columnName2" => "value2B",
            ),
            array(
                "columnName2" => "value2C",
                "columnName3" => "value3C",
                "columnName1" => "value1C",
            ),
        );

        $expectedRows = array(
            array(
                "columnName1" => "value1A",
                "columnName2" => "value2A",
                "columnName3" => "value3A",
            ),
            array(
                "columnName1" => "value1B",
                "columnName2" => "value2B",
                "columnName3" => "value3B",
            ),
            array(
                "columnName1" => "value1C",
                "columnName2" => "value2C",
                "columnName3" => "value3C",
            ),
        );

        $this->assertWritingMultipleRows($inputRows, $expectedRows);
    }

    function testAppendingUnsortedDataToExistingFile(){
        $writer1 = $this->createWriter();
        $writer1->writeRow(array(
            "columnName1" => "value1A",
            "columnName2" => "value2A",
            "columnName3" => "value3A",
        ));

        $writer2 = $this->createWriter();
        $writer2->writeRow(array(
            "columnName2" => "value2B",
            "columnName3" => "value3B",
            "columnName1" => "value1B",
        ));

        $actualRows = $this->readTestFileContent();
        $expectedRows = array(
            array(
                "columnName1" => "value1A",
                "columnName2" => "value2A",
                "columnName3" => "value3A",
            ),
            array(
                "columnName1" => "value1B",
                "columnName2" => "value2B",
                "columnName3" => "value3B",
            )
        );

        Assert::areIdentical($expectedRows, $actualRows);
    }

    function testWritingNotPreviouslyExistingColumnsThrowsException(){
        $writer = $this->createWriter();

        $inputRows = array(
            array(
                "columnName1" => "value1A",
                "columnName2" => "value2A",
            ),
            array(
                "columnName2" => "value2B",
                "columnName3" => "value3B",
            ),
        );

        $writer->writeRow($inputRows[0]);

        $exceptionThrown = false;
        try {
            $writer->writeRow($inputRows[1]);
        } catch (\WriterException $e){
            $exceptionThrown = true;
        }

        Assert::isTrue($exceptionThrown);
    }

    function testNotWritingPreviouslyExistingColumns(){
        $inputRows = array(
            array(
                "columnName1" => "value1A",
                "columnName2" => "value2A",
                "columnName3" => "value3A",
            ),
            array(
                "columnName1" => "value1B",
                "columnName3" => "value3B",
            ),
        );

        $expectedRows = array(
            array(
                "columnName1" => "value1A",
                "columnName2" => "value2A",
                "columnName3" => "value3A",
            ),
            array(
                "columnName1" => "value1B",
                "columnName2" => "",
                "columnName3" => "value3B",
            ),
        );

        $this->assertWritingMultipleRows($inputRows, $expectedRows);
    }

}