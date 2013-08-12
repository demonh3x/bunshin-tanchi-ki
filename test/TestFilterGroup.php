<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/FilterGroup.php");

include_once(__ROOT_DIR__ . "test/mocks/NoSpacesMockFilter.php");
include_once(__ROOT_DIR__ . "test/mocks/UppercaseMockFilter.php");

class TestFilterGroup extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createFilterGroup(Array $filters = array()){
        return Core::getCodeCoverageWrapper("FilterGroup", array($filters));
    }

    function testNoFilters(){
        $group = $this->createFilterGroup();
        Assert::areIdentical("hi", $group->applyTo("hi"));
    }

    function testOneFilter(){
        $group = $this->createFilterGroup(array(new UppercaseMockFilter()));
        Assert::areIdentical("HI", $group->applyTo("hi"));
    }

    function testTwoFiltersOneWay(){
        $group = $this->createFilterGroup(array(
            new NoSpacesMockFilter(),
            new UppercaseMockFilter()
        ));
        Assert::areIdentical("HI", $group->applyTo(" h i "));
    }

    function testTwoFiltersReversed(){
        $group = $this->createFilterGroup(array(
            new UppercaseMockFilter(),
            new NoSpacesMockFilter()
        ));
        Assert::areIdentical("HI", $group->applyTo(" h i "));
    }

    function testGroupCreationByParameters(){
        $group = $this->createFilterGroup(array(
            new UppercaseMockFilter(),
            new NoSpacesMockFilter()
        ));
        Assert::areIdentical("HI", $group->applyTo(" h i "));
    }
}
