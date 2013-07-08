<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Comparators/Filters/CutFromFirstSpaceFilter.php");

class TestCutFromFirstSpaceFilter extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    function testTrimmedOutput(){
        $filter = Core::getCodeCoverageWrapper("CutFromFirstSpaceFilter");
        $input = "Hello World!";
        $expected = "Hello";
        Assert::areIdentical($expected, $filter->applyTo($input));
    }
}