<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/DuplicatesScanner.php");

class TestDuplicatesScanner extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function duplicatesScannerFactory(){
        return Core::getCodeCoverageWrapper('DuplicatesScanner');
    }

    function testRaiseExceptionWhenSettingAReaderNotReady(){
        $scanner = $this->duplicatesScannerFactory();
        $exceptionRaised = false;

        try {
            $scanner->setReader(new NotReadyMockReader());
        } catch(\Exception $e){
            $exceptionRaised = true;
        }

        Assert::isTrue($exceptionRaised);
    }

    function testGetUniquesCheckingAllColumnsWhenNoDuplicates(){
        $scanner = $this->duplicatesScannerFactory();

        $resource = array(
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
        $reader = new MockReader();
        $reader->setResource($resource);
        $scanner->setReader($reader);

        $comparator = new MockComparator();
        $scanner->setColumnComparator($comparator);

        Assert::areIdentical($resource, $scanner->getUniques());
    }

    function testGetUniquesCheckingAllColumnsWhenDuplicates(){
        $scanner = $this->duplicatesScannerFactory();

        $resource = array(
            array(
                "0" => "", "1" => "Luxlo", "2" => "Property", "3" => "Amit", "4" => "Chadha",
                "5" => "www.amayadesign.co.uk/AmitChadha", "6" => "www.amayadesign.co.uk/",
                "7" => "AmitChadha", "8" => "Y", "9" => ""
            ),
            array(
                "0" => "", "1" => "タマ", "2" => "いぬ", "3" => "", "4" => "",
                "5" => "", "6" => "",
                "7" => "", "8" => "", "9" => ""
            ),
            array(
                "0" => "", "1" => "タマ", "2" => "いぬ", "3" => "", "4" => "",
                "5" => "", "6" => "",
                "7" => "", "8" => "", "9" => ""
            ),
            array(
                "0" => "NO", "1" => "タマ", "2" => "いぬ", "3" => "", "4" => "",
                "5" => "", "6" => "",
                "7" => "", "8" => "", "9" => ""
            )
        );
        $reader = new MockReader();
        $reader->setResource($resource);
        $scanner->setReader($reader);

        $comparator = new MockComparator();
        $scanner->setColumnComparator($comparator);

        $expected = array(
            array(
                "0" => "", "1" => "Luxlo", "2" => "Property", "3" => "Amit", "4" => "Chadha",
                "5" => "www.amayadesign.co.uk/AmitChadha", "6" => "www.amayadesign.co.uk/",
                "7" => "AmitChadha", "8" => "Y", "9" => ""
            ),
            array(
                "0" => "", "1" => "タマ", "2" => "いぬ", "3" => "", "4" => "",
                "5" => "", "6" => "",
                "7" => "", "8" => "", "9" => ""
            ),
            array(
                "0" => "NO", "1" => "タマ", "2" => "いぬ", "3" => "", "4" => "",
                "5" => "", "6" => "",
                "7" => "", "8" => "", "9" => ""
            )
        );

        Assert::areIdentical($expected, $scanner->getUniques());
    }

    /**
     * This benchmark is made to measure how much time does it take to analise 100,000 rows.
     * It is going to take a while to execute and use a considerable amount of ram.
     */
/*    function testBenchmark100000(){
        $scanner = $this->duplicatesScannerFactory();

        $uniqueData = array(
            "0" => "", "1" => "Luxlo", "2" => "Property", "3" => "Amit", "4" => "Chadha",
            "5" => "www.amayadesign.co.uk/AmitChadha", "6" => "www.amayadesign.co.uk/",
            "7" => "AmitChadha", "8" => "Y", "9" => ""
        );
        $resource = array();
        for ($i = 0; $i < 100000; $i++){
            $resource[] = $uniqueData;
        }

        $reader = new MockReader();
        $reader->setResource($resource);
        $scanner->setReader($reader);

        $comparator = new MockComparator();
        $scanner->setColumnComparator($comparator);

        Assert::areIdentical(array($uniqueData), $scanner->getUniques());

        $memory = memory_get_usage(true) / 1024 / 1024;
        echo "<h1>100.000 Duplicates benchmark memory usage: $memory MB</h1>";
    }*/
}

class NotReadyMockReader implements \Reader{
    function open($path){
    }
    function isReady(){
        return false;
    }
    function readRow(){
        return array();
    }
    function isEof(){
        return false;
    }
}

class MockReader implements \Reader{
    private $cursor = 0, $resource = array();

    function setResource($resource){
        $this->resource = $resource;
    }

    function open($path){
    }
    function isReady(){
        return true;
    }
    function readRow(){
        $data = $this->resource[$this->cursor];

        if(!$this->isEof()){
            $this->cursor++;
        }

        return $data;
    }
    function isEof(){
        return $this->cursor >= count($this->resource);
    }
}

class MockComparator implements \Comparator{
    function areEqual($a, $b){
        return $a === $b;
    }
}