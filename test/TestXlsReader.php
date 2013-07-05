<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Readers/XlsReader.php");

class TestXlsReader extends TestFixture{

    private $testDataXls;

    public function setUp(){
        $this->testDataXls =  __ROOT_DIR__ . 'test/sampleFiles/test_data.xls';
    }

    public function tearDown(){
    }

    private function xlsReaderFactory(){
        return Core::getCodeCoverageWrapper('XlsReader');
    }

    private function createTestDataXlsReader(){
        $reader = $this->xlsReaderFactory();
        $reader->open($this->testDataXls);
        return $reader;
    }

    function testNotReadyOnCreate(){
        $reader = $this->xlsReaderFactory();
        Assert::isFalse($reader->isReady());
    }

    function testOpenNonExistingFile(){
        $reader = $this->xlsReaderFactory();
        $reader->open('');
        Assert::isFalse($reader->isReady());
    }

    function testOpenFile(){
        $reader = $this->createTestDataXlsReader();
        Assert::isTrue($reader->isReady());
    }

    function testReadFirstRow(){
        $reader = $this->createTestDataXlsReader();
        $reader->open($this->testDataXls);

        $result = array(
            "A" => "ID",
            "B" => "Company",
            "C" => "Salutation",
            "D" => "Firstname",
            "E" => "Surname",
            "F" => "PrintPURL",
            "G" => "Domain_name"
        );
        Assert::areIdentical($result, $reader->readRow());
        
    }

    function testReadThreelRows(){
        $reader = $this->createTestDataXlsReader();

        $expectedResults = array(
            array(
                "A" => NULL, "B" => "Finchatton", "C" => NULL, "D" => "Adam", "E" => "Hunter",
                "F" => "www.amayadesign.co.uk/AdamHunter", "G" => "www.amayadesign.co.uk/"
            ),
            array(
                "A" => NULL, "B" => "Luxlo Property", "C" => NULL, "D" => "Amit", "E" => "Chadha",
                "F" => "www.amayadesign.co.uk/AmitChadha", "G" => "www.amayadesign.co.uk/"
            ),
        );

        $reader->readRow();
        $actualResults = array(
            $reader->readRow(),
            $reader->readRow()
        );
        Assert::areIdentical($expectedResults, $actualResults);
    }

    function testNotEOF(){
        $reader = $this->createTestDataXlsReader();

        Assert::isFalse($reader->isEof());
    }

    function testReadUntilEOF(){
        $reader = $this->createTestDataXlsReader();

        $loopLimit = 100000;
        $rowCounter = 0;
        while (!$reader->isEof() &&
                $rowCounter < $loopLimit){
            $reader->readRow();
            $rowCounter++;
        }
        $sampleXlsRows = 9;

        Assert::areIdentical($sampleXlsRows, $rowCounter);
    }

    function testEmptyFile(){
        $reader = $this->xlsReaderFactory();
        $reader->open( __ROOT_DIR__ . 'test/sampleFiles/test_empty_data.xls');

        Assert::isTrue($reader->isEof());
    }
}