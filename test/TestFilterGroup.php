<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Comparators/Filters/FilterGroup.php");

class TestFilterGroup extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createFilterGroup(){
        return Core::getCodeCoverageWrapper("FilterGroup");
    }

    function testNoFilters(){
        $group = $this->createFilterGroup();
        Assert::areIdentical("hi", $group->filter("hi"));
    }

    function testOneFilter(){
        $group = $this->createFilterGroup();
        $group->addFilter(new UppercaseMockFilter());
        Assert::areIdentical("HI", $group->filter("hi"));
    }

    function testTwoFiltersOneWay(){
        $group = $this->createFilterGroup();
        $group->addFilter(new NoSpacesMockFilter());
        $group->addFilter(new UppercaseMockFilter());
        Assert::areIdentical("HI", $group->filter(" h i "));
    }

    function testTwoFiltersReversed(){
        $group = $this->createFilterGroup();
        $group->addFilter(new UppercaseMockFilter());
        $group->addFilter(new NoSpacesMockFilter());
        Assert::areIdentical("HI", $group->filter(" h i "));
    }
}

class NoSpacesMockFilter implements \Filter{
    function filter($text){
        switch($text){
            case " h i ":
                return "hi";
            case " H I ":
                return "HI";
            default:
                throw new \Exception("NoSpacesMockFilter's case ($text) is not defined");
        }
    }
}

class UppercaseMockFilter implements \Filter{
    function filter($text){
        switch($text){
            case "hi":
                return "HI";
            case " h i ":
                return " H I ";
            default:
                throw new \Exception("UppercaseMockFilter's case ($text) is not defined");
        }
    }
}