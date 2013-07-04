<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Filters/OnlyLettersFilter.php");

class TestOnlyLettersFilter extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    function testRemovingNotCommonLetters(){
        $filter = new \OnlyLettersFilter();
        $input = "Hello $%&(+World! ";
        $expected = "Hello World ";
        Assert::areIdentical($expected, $filter->filter($input));
    }


}