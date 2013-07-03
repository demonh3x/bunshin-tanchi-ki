<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/CsvReader.php");

class TestCsvReader extends TestFixture{

    private $sampleCsv;

    public function setUp(){
        $this->sampleCsv =  __ROOT_DIR__ . 'test/sampleFiles/test_data.csv';
    }

    public function tearDown(){
    }

    function testNotReadyOnCreate(){
        $reader = new \CsvReader();
        Assert::isFalse($reader->isReady());
    }

    function testOpenNonExistingFile(){
        $reader = new \CsvReader();
        $reader->open('');
        Assert::isFalse($reader->isReady());
    }

    function testOpenFile(){
        $reader = new \CsvReader();
        $reader->open($this->sampleCsv);
        Assert::isTrue($reader->isReady());
    }

    function testReadFirstRow(){
        $reader = new \CsvReader();
        $reader->open($this->sampleCsv);

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
        $reader = new \CsvReader();
        $reader->open($this->sampleCsv);

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

    //TODO: testReadUntilEOF()
}