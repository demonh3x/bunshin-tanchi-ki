<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Writers/RamWriter.php");

class TestRamWriter extends TestFixture{

    private $globalVariableName = "testRamWriterGlobalVariable";

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createWriter($id){
        return Core::getCodeCoverageWrapper("RamWriter", array($id));
    }

    function testCreatingDefinesGlobalVariable(){
        $this->createWriter($this->globalVariableName);

        $isGlobalVariableDefined = isset($GLOBALS[$this->globalVariableName]);

        $this->undefineGlobal();

        Assert::isTrue($isGlobalVariableDefined);
    }

    private function undefineGlobal(){
        unset($GLOBALS[$this->globalVariableName]);
    }

    function testCreatingMakesAnArray(){
        $this->createWriter($this->globalVariableName);

        $isGlobalVariableAnArray = is_array($GLOBALS[$this->globalVariableName]);

        $this->undefineGlobal();

        Assert::isTrue($isGlobalVariableAnArray);
    }

    function testCreatingWhenAlreadyDefinedGlobalThrowsAnOutputExceptionWithCode100(){
        $this->defineGlobalVariable("Foo");

        $exceptionThrown = false;
        $errorCode = null;
        try {
            $this->createWriter($this->globalVariableName);
        } catch (\WriterException $e){
            $exceptionThrown = true;
            $errorCode = $e->getCode();
        }

        $this->undefineGlobal();

        Assert::isTrue($exceptionThrown);
        Assert::areIdentical(100, $errorCode);
    }

    private function defineGlobalVariable($value){
        $GLOBALS[$this->globalVariableName] = $value;
    }

    function testWritingRow(){
        $writer = $this->createWriter($this->globalVariableName);

        $row = array(
            "0" => "Foo",
            "1" => "Bar"
        );
        $writer->writeRow($row);

        $expected = array($row);
        $actual = $this->getGlobalVariable();

        $this->undefineGlobal();

        Assert::areIdentical($expected, $actual);
    }

    private function getGlobalVariable(){
        return $GLOBALS[$this->globalVariableName];
    }
}