<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Readers/CsvReader.php");

class TestCsvReader extends TestFixture{

    private $testDataCsv;

    public function setUp(){
        $this->testDataCsv =  __ROOT_DIR__ . 'test/sampleFiles/test_data.csv';
    }

    public function tearDown(){
    }

    private function csvReaderFactory(){
        return Core::getCodeCoverageWrapper('CsvReader');
    }

    private function createTestDataCsvReader(){
        $reader = $this->csvReaderFactory();
        $reader->open($this->testDataCsv);

        return $reader;
    }

    function testNotReadyOnCreate(){
        $reader = $this->csvReaderFactory();
        Assert::isFalse($reader->isReady());
    }

    function testOpenNonExistingFile(){
        $reader = $this->csvReaderFactory();
        $reader->open('');
        Assert::isFalse($reader->isReady());
    }

    function testOpenFile(){
        $reader = $this->createTestDataCsvReader();
        Assert::isTrue($reader->isReady());
    }

    function testReadFirstRow(){
        $reader = $this->createTestDataCsvReader();

        $result = array(
            "0" => "",
            "1" => "Finchatton",
            "2" => "",
            "3" => "Adam",
            "4" => "Hunter",
            "5" => "www.amayadesign.co.uk/AdamHunter",
            "6" => "www.amayadesign.co.uk/",
            "7" => "AdamHunter",
            "8" => "Y",
            "9" => ""
        );
        Assert::areIdentical($result, $reader->readRow());
    }

    function testReadThreelRows(){
        $reader = $this->createTestDataCsvReader();

        $expectedResults = array(
            array(
                "0" => "", "1" => "Luxlo", "2" => "Property", "3" => "Amit", "4" => "Chadha",
                "5" => "www.amayadesign.co.uk/AmitChadha", "6" => "www.amayadesign.co.uk/",
                "7" => "AmitChadha", "8" => "Y", "9" => ""
            ),
            array(
                "0" => "", "1" => "タマ", "2" => "いぬ", "3" => "", "4" => "",
                "5" => "", "6" => "",
                "7" => "", "8" => "", "9" => ""
            )
        );

        $reader->readRow();
        $actualResults = array(
            $reader->readRow(),
            $reader->readRow()
        );
        Assert::areIdentical($expectedResults, $actualResults);
    }

    function testNotEOF(){
        $reader = $this->createTestDataCsvReader();

        Assert::isFalse($reader->isEof());
    }

    function testReadUntilEOF(){
        $reader = $this->createTestDataCsvReader();

        $loopLimit = 100000;
        $rowCounter = 0;
        while (!$reader->isEof() &&
                $rowCounter < $loopLimit){
            $reader->readRow();
            $rowCounter++;
        }
        $sampleCsvRows = 5;

        Assert::areIdentical($sampleCsvRows, $rowCounter);
    }

    function testEmptyFile(){
        $reader = $this->csvReaderFactory();
        $reader->open( __ROOT_DIR__ . 'test/sampleFiles/test_empty_data.csv');

        Assert::isTrue($reader->isEof());
    }
}