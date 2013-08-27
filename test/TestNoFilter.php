<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/NullFilter.php");

class TestNoFilter extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    function testSameInputAndOutput(){
        $filter = Core::getCodeCoverageWrapper("NullFilter");
        $input = "Hello World!";
        Assert::areIdentical($input, $filter->applyTo($input));
    }
}