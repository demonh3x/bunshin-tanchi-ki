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

    private function createHashCalculator($watchColumns = array(), \RowFilter $filter = null){
        return Core::getCodeCoverageWrapper('StringHashCalculator', array($watchColumns, $filter));
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
        $calculator = $this->createHashCalculator(array("2"));

        $data = array(
            "0" => "foo",
            "1" => "bar",
            "2" => "asdf",
            "3" => "qwer"
        );

        Assert::areIdentical("2asdf", $calculator->calculate($data));
    }

    function testSelectedColumnsHash(){
        $calculator = $this->createHashCalculator(array("0", "3"));

        $data = array(
            "0" => "foo",
            "1" => "bar",
            "2" => "asdf",
            "3" => "qwer"
        );

        Assert::areIdentical("0foo3qwer", $calculator->calculate($data));
    }

    function testFilteredHash(){
        $filter = new \PerColumnRowFilter(array(
            "0" => new LowercaseMockFilter()
        ));
        $calculator = $this->createHashCalculator(array(), $filter);

        $data = array(
            "0" => "Foo"
        );

        Assert::areIdentical("0foo", $calculator->calculate($data));
    }

    function testDifferentFiltersPerColumn(){
        $filter = new \PerColumnRowFilter(array(
            "0" => new LowercaseMockFilter(),
            "1" => new RemoveSpacesMockFilter()
        ));
        $calculator = $this->createHashCalculator(array(), $filter);

        $data = array(
            "0" => "Foo",
            "1" => " B a r "
        );

        Assert::areIdentical("0foo1Bar", $calculator->calculate($data));
    }

    function testSelectedColumnsWithFilters(){
        $filter = new \PerColumnRowFilter(array(
            "1" => new LowercaseMockFilter(),
            "3" => new RemoveSpacesMockFilter()
        ));
        $calculator = $this->createHashCalculator(array("3", "1"), $filter);

        $data = array(
            "0" => "asdf",
            "1" => "Foo",
            "2" => "qwer",
            "3" => " B a r "
        );

        Assert::areIdentical("3Bar1foo", $calculator->calculate($data));
    }

    function testFilteringMultipleColumnsAtOnce(){
        $lowercaseFilter = new LowercaseMockFilter();
        $filter = new \PerColumnRowFilter(array(
            "1" => $lowercaseFilter,
            "3" => $lowercaseFilter
        ));
        $calculator = $this->createHashCalculator(array("3", "1"), $filter);

        $data = array(
            "0" => "asdf",
            "1" => "Foo",
            "2" => "qwer",
            "3" => "Bar"
        );

        Assert::areIdentical("3bar1foo", $calculator->calculate($data));
    }

    function testSelectingNonExistingColumns(){
        $calculator = $this->createHashCalculator(array("5", "asdf"));

        $data = array(
            "0" => "asdf",
            "1" => "Foo",
            "2" => "qwer",
            "3" => " B a r "
        );

        Assert::areIdentical("", $calculator->calculate($data));
    }

    function testSelectingSomeNonExistingColumns(){
        $calculator = $this->createHashCalculator(array("1", "asdf"));

        $data = array(
            "0" => "asdf",
            "1" => "Foo",
            "2" => "qwer",
            "3" => " B a r "
        );

        Assert::areIdentical("1Foo", $calculator->calculate($data));
    }

    function testNullValues(){
        $calculator = $this->createHashCalculator(array("0", "1"));

        $data = array(
            "0" => null,
            "1" => "Foo",
            "2" => "qwer",
            "3" => " B a r "
        );

        Assert::areIdentical("1Foo", $calculator->calculate($data));
    }
}