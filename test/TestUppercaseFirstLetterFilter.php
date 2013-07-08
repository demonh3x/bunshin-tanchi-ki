<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/UppercaseFirstLetterFilter.php");

class TestUppercaseFirstLetterFilter extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createFilter(){
        return Core::getCodeCoverageWrapper("UppercaseFirstLetterFilter");
    }

    function testUppercasedInput(){
        $filter = $this->createFilter();
        $input = "HELLO!";
        $expected = "Hello!";
        Assert::areIdentical($expected, $filter->applyTo($input));
    }

    function testLowercasedInput(){
        $filter = $this->createFilter();
        $input = "hello!";
        $expected = "Hello!";
        Assert::areIdentical($expected, $filter->applyTo($input));
    }

    function testMixcasedInput(){
        $filter = $this->createFilter();
        $input = "hELlO!";
        $expected = "Hello!";
        Assert::areIdentical($expected, $filter->applyTo($input));
    }

    function testThreeWordsUppercasedInput(){
        $filter = $this->createFilter();
        $input = "HELLO BIG WORLD!";
        $expected = "Hello Big World!";
        Assert::areIdentical($expected, $filter->applyTo($input));
    }

    function testThreeWordsLowercasedInput(){
        $filter = $this->createFilter();
        $input = "hello big world!";
        $expected = "Hello Big World!";
        Assert::areIdentical($expected, $filter->applyTo($input));
    }

    function testThreeWordsMixcasedInput(){
        $filter = $this->createFilter();
        $input = "hELlO bIg WoRlD!";
        $expected = "Hello Big World!";
        Assert::areIdentical($expected, $filter->applyTo($input));
    }
}