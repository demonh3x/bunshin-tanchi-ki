<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/RandomReaders/RamRandomReader.php");

class TestRamRandomReader extends TestFixture{

    private $globalVariableName = "testRamRandomReaderGlobalVariable";

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createReader(){
        return Core::getCodeCoverageWrapper("RamRandomReader");
    }

    function testOpeningNotDefinedGlobal(){
        $reader = $this->createReader();

        $reader->open("nonExistingGlobalVariable");
        Assert::isFalse($reader->isReady());
        Assert::areIdentical(0, $reader->getRowCount());
    }

    function testOpeningNonArrayDefinedGlobal(){
        $reader = $this->createReader();

        $this->defineGlobalVariable("Foo");

        $reader->open($this->globalVariableName);
        $ready = $reader->isReady();

        $this->undefineGlobalVariable();

        Assert::isFalse($ready);
    }

    function testOpeningArrayDefinedGlobal(){
        $reader = $this->createReader();

        $this->defineGlobalVariable(array());

        $reader->open($this->globalVariableName);
        $ready = $reader->isReady();

        $this->undefineGlobalVariable();

        Assert::isTrue($ready);
    }

    private function defineGlobalVariable($value){
        $GLOBALS[$this->globalVariableName] = $value;
    }

    private function undefineGlobalVariable(){
        unset($GLOBALS[$this->globalVariableName]);
    }

    function testRowCount(){
        $reader = $this->createReader();

        $this->defineGlobalVariable(
            array(
                array("0" => "Foo"),
                array("0" => "Bar")
            )
        );
        $reader->open($this->globalVariableName);
        Assert::areIdentical(2, $reader->getRowCount());
    }

    function testReadingRow(){
        $reader = $this->createReader();

        $this->defineGlobalVariable(
            array(
                array("0" => "Foo"),
                array("0" => "Bar")
            )
        );
        $reader->open($this->globalVariableName);
        $expected = array("0" => "Bar");

        Assert::areIdentical($expected, $reader->readRow(1));
    }
}