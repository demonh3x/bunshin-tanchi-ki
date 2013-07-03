<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/CsvReader.php");

class TestCsvReader extends TestFixture{

    private $sampleCsv;

    public function setUp(){
        $this->sampleCsv =  __ROOT_DIR__ . 'test/sampleFiles/amaya_data_template.csv';
    }

    public function tearDown(){
    }

    function testOpenFile(){
        $reader = new \CsvReader();
        Assert::isFalse($reader->isReady());

        $reader->openFile($this->sampleCsv);
        Assert::isTrue($reader->isReady());
    }

    function testReadFirstRow(){
        $reader = new \CsvReader();
        $reader->openFile($this->sampleCsv);

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
}