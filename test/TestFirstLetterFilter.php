<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/FirstLetterFilter.php");

class TestFirstLetterFilter extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createFilter(){
        return Core::getCodeCoverageWrapper("FirstLetterFilter");
    }

    function testInput(){
        $filter = $this->createFilter();
        $input = "Hello!";
        $expected = "H";
        Assert::areIdentical($expected, $filter->applyTo($input));
    }

    function testOneLetterInput(){
        $filter = $this->createFilter();
        $input = "H";
        $expected = "H";
        Assert::areIdentical($expected, $filter->applyTo($input));
    }

    function testEmptyInput(){
        $filter = $this->createFilter();
        $input = "";
        $expected = "";
        Assert::areIdentical($expected, $filter->applyTo($input));
    }
}