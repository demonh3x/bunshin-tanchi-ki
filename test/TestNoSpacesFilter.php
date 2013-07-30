<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/NoSpacesFilter.php");

class TestNoSpacesFilter extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    function testNoSpacesOutput(){
        $filter = Core::getCodeCoverageWrapper("NoSpacesFilter");
        $input = "H ello Wor ld !";
        $expected = "HelloWorld!";
        Assert::areIdentical($expected, $filter->applyTo($input));
    }
}