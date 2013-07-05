<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashList.php");

class TestHashList extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function uniqueListFactory(){
        return Core::getCodeCoverageWrapper("HashList");
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
}