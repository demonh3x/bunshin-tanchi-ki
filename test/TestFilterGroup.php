<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Comparators/Filters/FilterGroup.php");

include_once(__ROOT_DIR__ . "test/mocks/NoSpacesMockFilter.php");
include_once(__ROOT_DIR__ . "test/mocks/UppercaseMockFilter.php");

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
        Assert::areIdentical("hi", $group->applyTo("hi"));
    }

    function testOneFilter(){
        $group = $this->createFilterGroup();
        $group->addFilter(new UppercaseMockFilter());
        Assert::areIdentical("HI", $group->applyTo("hi"));
    }

    function testTwoFiltersOneWay(){
        $group = $this->createFilterGroup();
        $group->addFilter(new NoSpacesMockFilter());
        $group->addFilter(new UppercaseMockFilter());
        Assert::areIdentical("HI", $group->applyTo(" h i "));
    }

    function testTwoFiltersReversed(){
        $group = $this->createFilterGroup();
        $group->addFilter(new UppercaseMockFilter());
        $group->addFilter(new NoSpacesMockFilter());
        Assert::areIdentical("HI", $group->applyTo(" h i "));
    }
}
