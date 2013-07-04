<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Filters/LowercaseFilter.php");

class TestLowercaseFilter extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    function testLowercasedOutput(){
        $filter = new \LowercaseFilter();
        $input = "Hello World!";
        $expected = "hello world!";
        Assert::areIdentical($expected, $filter->filter($input));
    }
}