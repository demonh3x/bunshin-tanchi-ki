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

    function testTwoColumnHash(){
        $calculator = $this->createHashCalculator();

        $data = array(
            "0" => "foo",
            "1" => "bar",
        );

        Assert::areIdentical("0foo1bar", $calculator->calculate($data));
    }

    function testSelectedColumnHash(){
        $calculator = $this->createHashCalculator();

        $data = array(
            "0" => "foo",
            "1" => "bar",
            "2" => "asdf",
            "3" => "qwer"
        );

        $calculator->watchColumns(array("2"));

        Assert::areIdentical("2asdf", $calculator->calculate($data));
    }

    function testSelectedColumnsHash(){
        $calculator = $this->createHashCalculator();

        $data = array(
            "0" => "foo",
            "1" => "bar",
            "2" => "asdf",
            "3" => "qwer"
        );

        $calculator->watchColumns(array("0", "3"));

        Assert::areIdentical("0foo3qwer", $calculator->calculate($data));
    }

}