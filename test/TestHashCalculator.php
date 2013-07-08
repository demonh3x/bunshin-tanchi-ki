<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashCalculator.php");

class TestHashCalculator extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createHashCalculator(){
        return Core::getCodeCoverageWrapper('HashCalculator');
    }

    function testOneColumnHash(){
        $calculator = $this->createHashCalculator();

        $data = array(
            "0" => "value"
        );

        Assert::areIdentical("0value", $calculator->calculate($data));
    }


}