<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/RandomReaders/RamRandomReader.php");

class TestRamRandomReader extends TestFixture{

    private $globalVariableName = "testRamRandomReaderGlobalVariable";

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createReader($id){
        return Core::getCodeCoverageWrapper("RamRandomReader", array($id));
    }

    function testOpeningNotDefinedGlobalThrowsAnInputExceptionWithCode100(){
        $exceptionThrown = false;

        try {
            $this->createReader("nonExistingGlobalVariable");
        } catch (\InputException $e){
            $exceptionThrown = true;
            Assert::areIdentical(100, $e->getCode());
        }

        Assert::isTrue($exceptionThrown);
    }

    function testOpeningNonArrayDefinedGlobalThrowsAnExceptionWithCode101(){
        $exceptionThrown = false;
        $this->defineGlobalVariable("Foo");

        try {
            $this->createReader($this->globalVariableName);
        } catch (\InputException $e){
            $exceptionThrown = true;
            Assert::areIdentical(101, $e->getCode());
        }

        $this->undefineGlobalVariable();

        Assert::isTrue($exceptionThrown);
    }

    function testOpeningArrayDefinedGlobalShouldNotThrowAnException(){
        $exceptionThrown = false;
        $this->defineGlobalVariable(array());

        try {
            $this->createReader($this->globalVariableName);
        } catch (\InputException $e){
            $exceptionThrown = true;
        }

        $this->undefineGlobalVariable();

        Assert::isFalse($exceptionThrown);
    }

    private function defineGlobalVariable($value){
        $GLOBALS[$this->globalVariableName] = $value;
    }

    private function undefineGlobalVariable(){
        unset($GLOBALS[$this->globalVariableName]);
    }

    function testRowCount(){
        $this->defineGlobalVariable(
            array(
                array("0" => "Foo"),
                array("0" => "Bar")
            )
        );

        $reader = $this->createReader($this->globalVariableName);

        Assert::areIdentical(2, $reader->getRowCount());
    }

    function testReadingRow(){
        $this->defineGlobalVariable(
            array(
                array("0" => "Foo"),
                array("0" => "Bar")
            )
        );

        $reader = $this->createReader($this->globalVariableName);

        $expected = array("0" => "Bar");

        Assert::areIdentical($expected, $reader->readRow(1));
    }
}