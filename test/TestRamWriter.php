<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Writers/RamWriter.php");

class TestRamWriter extends TestFixture{

    private $globalVariableName = "testRamWriterGlobalVariable";

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createWriter(){
        return Core::getCodeCoverageWrapper("RamWriter");
    }

    function testCreatingDefinesGlobalVariable(){
        $writer = $this->createWriter();

        $writer->create($this->globalVariableName);
        $isGlobalVariableDefined = isset($GLOBALS[$this->globalVariableName]);
        $isReady = $writer->isReady();

        $this->undefineGlobal();

        Assert::isTrue($isGlobalVariableDefined);
        Assert::isTrue($isReady);
    }

    private function undefineGlobal(){
        unset($GLOBALS[$this->globalVariableName]);
    }

    function testCreatingMakesAnArray(){
        $writer = $this->createWriter();

        $writer->create($this->globalVariableName);
        $isGlobalVariableAnArray = is_array($GLOBALS[$this->globalVariableName]);

        $this->undefineGlobal();

        Assert::isTrue($isGlobalVariableAnArray);
    }

    function testCreatingWhenAlreadyDefinedGlobal(){
        $writer = $this->createWriter();

        $this->defineGlobalVariable("Foo");
        $writer->create($this->globalVariableName);
        $isReady = $writer->isReady();

        $this->undefineGlobal();

        Assert::isFalse($isReady);
    }

    private function defineGlobalVariable($value){
        $GLOBALS[$this->globalVariableName] = $value;
    }

    function testWritingRow(){
        $writer = $this->createWriter();

        $writer->create($this->globalVariableName);
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