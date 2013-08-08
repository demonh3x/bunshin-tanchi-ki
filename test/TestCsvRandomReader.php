<?php
namespace Enhance;

include_once (__ROOT_DIR__ . "src/RandomReaders/CsvRandomReader.php");

class TestCsvRandomReader extends TestFixture{

    private $testDataCsv;

    public function setUp(){
        $this->testDataCsv =  __ROOT_DIR__ . 'test/sampleFiles/test_data.csv';
    }

    public function tearDown(){
    }

    private function createReader($path){
        return Core::getCodeCoverageWrapper("CsvRandomReader", array($path));
    }

    function testOpenNotValidPathThrowsARandomReaderExceptionWithCode200(){
        $exceptionThrown = false;

        try {
            $this->createReader('');
        } catch (\RandomReaderException $e){
            $exceptionThrown = true;
            Assert::areIdentical(200, $e->getCode());
        }

        Assert::isTrue($exceptionThrown);
    }

    function testOpenNonExistingFileThrowsARandomReaderExceptionWithCode201(){
        $exceptionThrown = false;

        try {
            $this->createReader('test/sampleFiles/non_existing_file.csv');
        } catch (\RandomReaderException $e){
            $exceptionThrown = true;
            Assert::areIdentical(201, $e->getCode());
        }

        Assert::isTrue($exceptionThrown);
    }

    function testReadEmptyFileCounts0Rows(){
        $reader = $this->createReader(__ROOT_DIR__ . 'test/sampleFiles/test_empty_data.csv');
        Assert::areIdentical(0, $reader->getRowCount());
    }

    private function createTestReader(){
        return $this->createReader($this->testDataCsv);
    }

    function testCountLines(){
        $reader = $this->createTestReader();
        $linesOfTheFile = 5;

        Assert::areIdentical($linesOfTheFile, $reader->getRowCount());
    }

    function testReadFirstRow(){
        $reader = $this->createTestReader();

        $expected = array(
            "0" => "",
            "1" => "Finchatton",
            "2" => "",
            "3" => "Adam",
            "4" => "Hunter",
            "5" => "www.amayadesign.co.uk/AdamHunter",
            "6" => "www.amayadesign.co.uk/",
            "7" => "AdamHunter",
            "8" => "Y",
            "9" => "£�"
        );
        $current = $reader->readRow(0);

        Assert::areIdentical($expected, $current);
    }

    function testReadThirdRow(){
        $reader = $this->createTestReader();

        $expected = array(
            "0" => "", "1" => "タマ", "2" => "いぬ", "3" => "", "4" => "",
            "5" => "", "6" => "",
            "7" => "", "8" => "", "9" => "£�"
        );
        $current = $reader->readRow(2);

        Assert::areIdentical($expected, $current);
    }

    function testJumpingForthToSecondRow(){
        $reader = $this->createTestReader();

        $expected = array(
            "0" => "",
            "1" => "Finchatton",
            "2" => "",
            "3" => "Adam",
            "4" => "Hunter",
            "5" => "www.amayadesign.co.uk/AdamHunter",
            "6" => "www.amayadesign.co.uk/",
            "7" => "AdamHunter",
            "8" => "Y",
            "9" => "£�"
        );
        $current = $reader->readRow(3);

        Assert::areIdentical($expected, $current);

        $expected = array(
            "0" => "", "1" => "Luxlo", "2" => "Property", "3" => "Amit", "4" => "Chadha",
            "5" => "www.amayadesign.co.uk/AmitChadha", "6" => "www.amayadesign.co.uk/",
            "7" => "AmitChadha", "8" => "Y", "9" => "£�"
        );
        $current = $reader->readRow(1);

        Assert::areIdentical($expected, $current);
    }
}