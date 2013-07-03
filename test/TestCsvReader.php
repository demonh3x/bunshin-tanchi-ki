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
        $reader->openFile($this->sampleCsv);
        Assert::isTrue($reader->isReady());
    }
}