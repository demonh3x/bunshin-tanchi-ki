<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashCalculators/RowFilter.php");
include_once(__ROOT_DIR__ . "test/mocks/LowercaseMockFilter.php");
include_once(__ROOT_DIR__ . "test/mocks/RemoveSpacesMockFilter.php");

class TestRowFilter extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createHashCalculator(){
        return Core::getCodeCoverageWrapper('RowFilter');
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

    function testGlobalFilter(){
        $calculator = $this->createHashCalculator();
        $calculator->setGlobalFilter(new LowercaseMockFilter());

        $input = array(
            "0" => "Foo",
            "Hi" => "Bar"
        );

        $output = array(
            "0" => "foo",
            "Hi" => "bar"
        );

        Assert::areIdentical(
            $output,
            $calculator->applyTo($input)
        );

        Assert::areNotIdentical($input, $output);
    }

    function testSingleColumnFilter(){
        $calculator = $this->createHashCalculator();
        $calculator->setFilter(
            new LowercaseMockFilter(),
            "0"
        );

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
        $calculator = $this->createHashCalculator();
        $calculator->setFilter(
            new LowercaseMockFilter(),
            array("0", "Hi")
        );

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

    function testGlobalFilterApplyBeforeColumnFilters(){
        $calculator = $this->createHashCalculator();
        $calculator->setGlobalFilter(
            new RemoveSpacesMockFilter()
        );
        $calculator->setFilter(
            new LowercaseMockFilter(),
            "Hi"
        );

        $input = array(
            "0" => " Hi",
            "Hi" => " B a r "
        );

        Assert::areIdentical(
            array(
                "0" => "Hi",
                "Hi" => "bar"
            ),
            $calculator->applyTo($input)
        );
    }

    function testMultipleColumnsMultipleFilters(){
        $calculator = $this->createHashCalculator();
        $calculator->setFilter(
            new LowercaseMockFilter(),
            0
        );
        $calculator->setFilter(
            new RemoveSpacesMockFilter(),
            "Hi"
        );

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