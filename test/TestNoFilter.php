<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Filters/NoFilter.php");

class TestNoFilter extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    function testSameInputAndOutput(){
        $filter = new \NoFilter();
        $input = "Hello World!";
        Assert::areIdentical($input, $filter->filter($input));
    }


}