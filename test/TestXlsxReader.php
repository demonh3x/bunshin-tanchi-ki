<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Readers/XlsxReader.php");

class TestXlsxReader extends TestFixture{

    private $testDataXlsx;

    public function setUp(){
        $this->testDataXlsx =  __ROOT_DIR__ . 'test/sampleFiles/test_data.xlsx';
    }

    public function tearDown(){
    }

    private function xlsxReaderFactory(){
        return Core::getCodeCoverageWrapper('XlsxReader');
    }

    private function createTestDataXlsxReader(){
        $reader = $this->xlsxReaderFactory();
        $reader->open($this->testDataXlsx);
        return $reader;
    }

    function testNotReadyOnCreate(){
        $reader = $this->xlsxReaderFactory();
        Assert::isFalse($reader->isReady());
    }

    function testOpenNonExistingFile(){
        $reader = $this->xlsxReaderFactory();
        $reader->open('');
        Assert::isFalse($reader->isReady());
    }

    function testOpenFile(){
        $reader = $this->createTestDataXlsxReader();
        Assert::isTrue($reader->isReady());
    }

    function testReadFirstRow(){
        $reader = $this->createTestDataXlsxReader();
        $reader->open($this->testDataXlsx);

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
        $reader = $this->createTestDataXlsxReader();

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
        $reader = $this->createTestDataXlsxReader();

        Assert::isFalse($reader->isEof());
    }

    function testReadUntilEOF(){
        $reader = $this->createTestDataXlsxReader();

        $loopLimit = 100000;
        $rowCounter = 0;
        while (!$reader->isEof() &&
                $rowCounter < $loopLimit){
            $reader->readRow();
            $rowCounter++;
        }
        $sampleXlsxRows = 9;

        Assert::areIdentical($sampleXlsxRows, $rowCounter);
    }

    function testEmptyFile(){
        $reader = $this->xlsxReaderFactory();
        $reader->open( __ROOT_DIR__ . 'test/sampleFiles/test_empty_data.xlsx');

        Assert::isTrue($reader->isEof());
    }
}