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


}