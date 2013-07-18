<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashCalculators/StringHashCalculator.php");
include_once(__ROOT_DIR__ . "test/mocks/LowercaseMockFilter.php");
include_once(__ROOT_DIR__ . "test/mocks/RemoveSpacesMockFilter.php");

class TestStringHashCalculator extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createHashCalculator(){
        return Core::getCodeCoverageWrapper('StringHashCalculator');
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

    function testSelectedColumnsWithFilters(){
        $calculator = $this->createHashCalculator();

        $data = array(
            "0" => "asdf",
            "1" => "Foo",
            "2" => "qwer",
            "3" => " B a r "
        );

        $calculator->watchColumns(array("3", "1"));
        $calculator->setFilter(new LowercaseMockFilter(), "1");
        $calculator->setFilter(new RemoveSpacesMockFilter(), "3");

        Assert::areIdentical("3Bar1foo", $calculator->calculate($data));
    }

    function testFilteringMultipleColumnsAtOnce(){
        $calculator = $this->createHashCalculator();

        $data = array(
            "0" => "asdf",
            "1" => "Foo",
            "2" => "qwer",
            "3" => "Bar"
        );

        $calculator->watchColumns(array("3", "1"));
        $calculator->setFilter(
            new LowercaseMockFilter(),
            array("1", "3")
        );

        Assert::areIdentical("3bar1foo", $calculator->calculate($data));
    }

    function testSelectingNonExistingColumns(){
        $calculator = $this->createHashCalculator();

        $data = array(
            "0" => "asdf",
            "1" => "Foo",
            "2" => "qwer",
            "3" => " B a r "
        );

        $calculator->watchColumns(array("5", "asdf"));

        Assert::areIdentical("", $calculator->calculate($data));
    }

    function testSelectingSomeNonExistingColumns(){
        $calculator = $this->createHashCalculator();

        $data = array(
            "0" => "asdf",
            "1" => "Foo",
            "2" => "qwer",
            "3" => " B a r "
        );

        $calculator->watchColumns(array("1", "asdf"));

        Assert::areIdentical("1Foo", $calculator->calculate($data));
    }

    function testNullValues(){
        $calculator = $this->createHashCalculator();

        $data = array(
            "0" => null,
            "1" => "Foo",
            "2" => "qwer",
            "3" => " B a r "
        );

        $calculator->watchColumns(array("0", "1"));

        Assert::areIdentical("1Foo", $calculator->calculate($data));
    }
}