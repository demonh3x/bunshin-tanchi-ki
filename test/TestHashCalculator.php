<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashCalculator.php");
include_once(__ROOT_DIR__ . "test/mocks/LowercaseMockFilter.php");
include_once(__ROOT_DIR__ . "test/mocks/RemoveSpacesMockFilter.php");

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

    function testFilteredHash(){
        $calculator = $this->createHashCalculator();

        $data = array(
            "0" => "Foo"
        );

        $calculator->setGlobalFilter(new LowercaseMockFilter());

        Assert::areIdentical("0foo", $calculator->calculate($data));
    }

    function testDifferentFiltersPerColumn(){
        $calculator = $this->createHashCalculator();

        $data = array(
            "0" => "Foo",
            "1" => " B a r "
        );
        $calculator->setFilter(new LowercaseMockFilter(), "0");
        $calculator->setFilter(new RemoveSpacesMockFilter(), "1");

        Assert::areIdentical("0foo1Bar", $calculator->calculate($data));
    }
}