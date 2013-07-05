<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashDuplicatesScanner.php");

class TestHashDuplicatesScanner extends TestFixture{
    public function setUp(){
    }

    public function tearDown(){
    }

    private function createScanner(){
        return Core::getCodeCoverageWrapper('HashDuplicatesScanner');
    }

    function testRaiseExceptionWhenSettingAReaderNotReady(){
        $scanner = $this->createScanner();
        $exceptionRaised = false;

        try {
            $scanner->setReader(new NotReadyMockReader());
        } catch(\Exception $e){
            $exceptionRaised = true;
        }

        Assert::isTrue($exceptionRaised);
    }

    private function createScannerWithReader($readerData){
        $scanner = $this->createScanner();

        $reader = new MockReader();
        $reader->setResource($readerData);
        $scanner->setReader($reader);

        return $scanner;
    }

    function testGettingUniquesWhenNoDuplicates(){
        $dataOneColumnNoDuplicates = array(
            array(
                "Column1" => "Foo"
            ),
            array(
                "Column1" => "Bar"
            )
        );

        $scanner = $this->createScannerWithReader($dataOneColumnNoDuplicates);
        Assert::areIdentical($dataOneColumnNoDuplicates, $scanner->getUniques());
    }

    function testGettingUniquesWhenDuplicates(){
        $dataOneColumnWithDuplicates = array(
            array(
                "Column1" => "Foo"
            ),
            array(
                "Column1" => "Bar"
            ),
            array(
                "Column1" => "Bar"
            )
        );

        $scanner = $this->createScannerWithReader($dataOneColumnWithDuplicates);

        $uniques = array(
            array(
                "Column1" => "Foo"
            ),

        );
        Assert::areIdentical($uniques, $scanner->getUniques());
    }
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
