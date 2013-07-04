<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Comparators/StringEqualityComparator.php");

class TestStringEqualityComparator extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    function testSameStringsWithoutFilters(){
        $comparator = new \StringEqualityComparator();
        Assert::isTrue($comparator->compare("Hi!", "Hi!"));
    }

    function testDifferentStringsWithoutFilters(){
        $comparator = new \StringEqualityComparator();
        Assert::isFalse($comparator->compare("Hi!", "Hello!"));
    }

    function testDifferentDataTypes(){
        $comparator = new \StringEqualityComparator();
        Assert::isFalse($comparator->compare(4, "4"));
    }

    function testOtherDataTypesThanString(){
        $comparator = new \StringEqualityComparator();
        Assert::isFalse($comparator->compare(4, 4));
    }

/*    function testSameStringsWithMockFilter(){

    }*/
}