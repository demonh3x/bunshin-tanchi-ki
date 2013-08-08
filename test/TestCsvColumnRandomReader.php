<?php
namespace Enhance;

include_once (__ROOT_DIR__ . "src/RandomReaders/CsvColumnRandomReader.php");

class TestCsvColumnRandomReader extends TestFixture{

    private $testDataCsv;

    public function setUp(){
        $this->testDataCsv =  __ROOT_DIR__ . 'test/sampleFiles/testCsvColumnRandomReader.csv';
    }

    public function tearDown(){
    }

    private function createReader($path){
        return Core::getCodeCoverageWrapper("CsvColumnRandomReader", array($path));
    }

    function testOpenNotValidPathThrowsARandomReaderExceptionWithCode2000(){
        $exceptionThrown = false;

        try {
            $this->createReader('');
        } catch (\RandomReaderException $e){
            $exceptionThrown = true;
            Assert::areIdentical(2000, $e->getCode());
        }

        Assert::isTrue($exceptionThrown);
    }

    function testOpenNonExistingFileThrowsARandomReaderExceptionWithCode2001(){
        $exceptionThrown = false;

        try {
            $this->createReader('test/sampleFiles/non_existing_file.csv');
        } catch (\RandomReaderException $e){
            $exceptionThrown = true;
            Assert::areIdentical(2001, $e->getCode());
        }

        Assert::isTrue($exceptionThrown);
    }
    function testReadEmptyFile(){
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
            "ID" => "",
            "Company" => "Finchatton",
            "Salutation" => "",
            "Firstname" => "Adam",
            "Surname" => "Hunter",
            "PrintPURL" => "www.amayadesign.co.uk/AdamHunter",
            "Domain_name" => "www.amayadesign.co.uk/",
            "PURL" => "AdamHunter",
            "Active" => "Y",
            "Jobtitle" => "£�"
        );
        $current = $reader->readRow(0);

        Assert::areIdentical($expected, $current);
    }

    function testReadThirdRow(){
        $reader = $this->createTestReader();

        $expected = array(
            "ID" => "",
            "Company" => "Barry Paterson & Associates",
            "Salutation" => "",
            "Firstname" => "Barry",
            "Surname" => "Paterson",
            "PrintPURL" => "www.amayadesign.co.uk/BarryPaterson",
            "Domain_name" => "www.amayadesign.co.uk/",
            "PURL" => "BarryPaterson",
            "Active" => "Y",
            "Jobtitle" => "£�"
        );
        $current = $reader->readRow(2);

        Assert::areIdentical($expected, $current);
    }

    function testJumpingFourthToSecondRow(){
        $reader = $this->createTestReader();

        $expected = array(
            "ID" => "",
            "Company" => "Peter Bryant",
            "Salutation" => "",
            "Firstname" => "Peter",
            "Surname" => "Bryant",
            "PrintPURL" => "www.amayadesign.co.uk/PeterBryant",
            "Domain_name" => "www.amayadesign.co.uk/",
            "PURL" => "PeterBryant",
            "Active" => "Y",
            "Jobtitle" => "£�"
        );
        $current = $reader->readRow(3);

        Assert::areIdentical($expected, $current);

        $expected = array(
            "ID" => "",
            "Company" => "Luxlo Property",
            "Salutation" => "",
            "Firstname" => "Amit",
            "Surname" => "Chadha",
            "PrintPURL" => "www.amayadesign.co.uk/AmitChadha",
            "Domain_name" => "www.amayadesign.co.uk/",
            "PURL" => "AmitChadha",
            "Active" => "Y",
            "Jobtitle" => "£�"
        );
        $current = $reader->readRow(1);

        Assert::areIdentical($expected, $current);
    }
}