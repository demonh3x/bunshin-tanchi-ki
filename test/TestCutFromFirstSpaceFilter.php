<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/CutFromFirstSpaceFilter.php");

class TestCutFromFirstSpaceFilter extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    function testInputtingTwoWordsOutputingOnlyFirst(){
        $filter = Core::getCodeCoverageWrapper("CutFromFirstSpaceFilter");
        $input = "Jason Mark";
        $expected = "Jason";
        Assert::areIdentical($expected, $filter->applyTo($input));
    }

    function testInputtingOneWordOutputtingTheSame(){
        $filter = Core::getCodeCoverageWrapper("CutFromFirstSpaceFilter");
        $input = "Jason";
        $expected = "Jason";
        Assert::areIdentical($expected, $filter->applyTo($input));
    }
}