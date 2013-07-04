<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Comparators/StringComparator.php");

class TestStringComparator extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    function testSameStringsWithoutFilters(){
        $comparator = new \StringComparator();
        Assert::isTrue($comparator->areEqual("Hi!", "Hi!"));
    }

    function testDifferentStringsWithoutFilters(){
        $comparator = new \StringComparator();
        Assert::isFalse($comparator->areEqual("Hi!", "Hello!"));
    }

    function testDifferentDataTypes(){
        $comparator = new \StringComparator();
        Assert::isFalse($comparator->areEqual(4, "4"));
    }

    function testOtherDataTypesThanString(){
        $comparator = new \StringComparator();
        Assert::isFalse($comparator->areEqual(4, 4));
    }

    function testOneFilterEqualValues(){
        $comparator = new \StringComparator();

        $firstColumn = " hi";
        $secondColumn = " h i ";

        $removeSpacesFilter = new RemoveSpacesMockFilter();
        $comparator->addFilter($removeSpacesFilter);

        Assert::isTrue($comparator->areEqual($firstColumn, $secondColumn));
    }

    function testOneFilterDifferentValues(){
        $comparator = new \StringComparator();

        $firstColumn = " hi";
        $secondColumn = " hello";

        $removeSpacesFilter = new RemoveSpacesMockFilter();
        $comparator->addFilter($removeSpacesFilter);

        Assert::isFalse($comparator->areEqual($firstColumn, $secondColumn));
    }

    function testTwoFiltersInOrderEqualValues(){
        $comparator = new \StringComparator();

        $firstColumn = " Hi";
        $secondColumn = " h I ";

        $comparator->addFilter(new RemoveSpacesMockFilter());
        $comparator->addFilter(new LowercaseMockFilter());

        Assert::isTrue($comparator->areEqual($firstColumn, $secondColumn));
    }
}

class RemoveSpacesMockFilter implements \Filter{
    function filter($text){
        switch($text){
            case " hi":
                return "hi";
            case " h i ":
                return "hi";
            case " hello":
                return "hello";
            case " Hi":
                return "Hi";
            case " h I ":
                return "hI";
            default:
                throw new \Exception("RemoveSpacesMockFilter's case not defined");
        }
    }
}

class LowercaseMockFilter implements \Filter{
    function filter($text){
        switch($text){
            case "Hi":
                return "hi";
            case "hI":
                return "hi";
            default:
                throw new \Exception("LowercaseMockFilter's case not defined");
        }
    }
}
