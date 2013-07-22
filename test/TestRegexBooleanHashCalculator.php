<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashCalculators/RegexBooleanHashCalculator.php");

class TestRegexBooleanHashCalculator extends TestFixture{

    private $TRUE_VALUE = "Found";

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createCalculator(){
        return Core::getCodeCoverageWrapper('RegexHashCalculator');
    }

    function testFailingRegex(){
        $calc = $this->createCalculator();

        $calc->setRegex('/a$/');
        $hash = $calc->calculate(array("0" => "asdf"));

        Assert::areNotIdentical($this->TRUE_VALUE, $hash);
    }

    function testMatchingRegex(){
        $calc = $this->createCalculator();

        $calc->setRegex('/^a/');
        $hash = $calc->calculate(array("0" => "asdf"));

        Assert::areIdentical($this->TRUE_VALUE, $hash);
    }

    function testNotRepeatingHashesWhenNotFound(){
        $calc = $this->createCalculator();

        $hashes = array();

        $calc->setRegex('/^z/');

        for ($i = 0; $i < 100; $i++){
            $hash = $calc->calculate(array("0" => "asdf"));

            Assert::isFalse(isset($hashes[$hash]));
            $hashes[$hash] = 0;
        }
    }
}