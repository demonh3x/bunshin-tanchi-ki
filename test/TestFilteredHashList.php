<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/FilteredHashList.php");
include_once("mocks/LowercaseMockFilter.php");

class TestFilteredHashList extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function uniqueListFactory(\Filter $filter = null){
        return Core::getCodeCoverageWrapper("FilteredHashList", array($filter));
    }

    function testContainsNotAddedValue(){
        $list = $this->uniqueListFactory();
        Assert::isFalse($list->contains("foo"));
    }

    function testContainsAddedValue(){
        $list = $this->uniqueListFactory();
        $list->add("foo");
        Assert::isTrue($list->contains("foo"));
    }

    function testContainsFilteredAddedValue(){
        $list = $this->uniqueListFactory(new LowercaseMockFilter());
        $list->add("Foo");
        Assert::isTrue($list->contains("FOO"));
    }
}