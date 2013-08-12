<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashCalculators/PerColumnRowFilter.php");
include_once(__ROOT_DIR__ . "test/mocks/LowercaseMockFilter.php");
include_once(__ROOT_DIR__ . "test/mocks/RemoveSpacesMockFilter.php");

class TestPerColumnRowFilter extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createHashCalculator($filters = array()){
        return Core::getCodeCoverageWrapper('PerColumnRowFilter', array($filters));
    }

    function testEmptyInput(){
        $calculator = $this->createHashCalculator();
        Assert::areIdentical(array(), $calculator->applyTo(array()));
    }

    function testWithoutFilters(){
        $calculator = $this->createHashCalculator();

        $input = array(
            "0" => "value"
        );

        Assert::areIdentical($input, $calculator->applyTo($input));
    }

    function testSingleColumnFilter(){
        $calculator = $this->createHashCalculator(array(
            "0" => new LowercaseMockFilter()
        ));

        $input = array(
            "0" => "Foo",
            "Hi" => "Bar"
        );

        Assert::areIdentical(
            array(
                "0" => "foo",
                "Hi" => "Bar"
            ),
            $calculator->applyTo($input)
        );
    }

    function testMultipleColumnsFilter(){
        $lowercaseFilter = new LowercaseMockFilter();

        $calculator = $this->createHashCalculator(array(
            "0" => $lowercaseFilter,
            "Hi" => $lowercaseFilter
        ));

        $input = array(
            "0" => "Foo",
            "Hi" => "Bar",
            "2" => "asdf"
        );

        Assert::areIdentical(
            array(
                "0" => "foo",
                "Hi" => "bar",
                "2" => "asdf"
            ),
            $calculator->applyTo($input)
        );
    }

    function testMultipleColumnsMultipleFilters(){
        $calculator = $this->createHashCalculator(array(
            0 => new LowercaseMockFilter(),
            "Hi" => new RemoveSpacesMockFilter()
        ));

        $input = array(
            0 => "Foo",
            "Hi" => " B a r ",
            "2" => "asdf"
        );

        Assert::areIdentical(
            array(
                0 => "foo",
                "Hi" => "Bar",
                "2" => "asdf"
            ),
            $calculator->applyTo($input)
        );
    }
}