<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Comparators/Filters/TrimFilter.php");

class TestTrimFilter extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    function testTrimmedOutput(){
        $filter = Core::getCodeCoverageWrapper("TrimFilter");
        $input = "  Hello World!  ";
        $expected = "Hello World!";
        Assert::areIdentical($expected, $filter->applyTo($input));
    }
}