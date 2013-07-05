<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Comparators/StringComparator.php");

include_once(__ROOT_DIR__ . "test/mocks/RemoveSpacesMockFilter.php");
include_once(__ROOT_DIR__ . "test/mocks/LowercaseMockFilter.php");

class TestStringComparator extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function stringComparatorFactory(){
        return Core::getCodeCoverageWrapper("StringComparator");
    }

    function testSameStringsWithoutFilters(){
        $comparator = $this->stringComparatorFactory();
        Assert::isTrue($comparator->areEqual("Hi!", "Hi!"));
    }

    function testDifferentStringsWithoutFilters(){
        $comparator = $this->stringComparatorFactory();
        Assert::isFalse($comparator->areEqual("Hi!", "Hello!"));
    }

    function testComparingSameStringAndIntValues(){
        $comparator = $this->stringComparatorFactory();
        Assert::isTrue($comparator->areEqual(4, "4"));
    }

    function testComparingDifferentStringAndIntValues(){
        $comparator = $this->stringComparatorFactory();
        Assert::isFalse($comparator->areEqual("5", 4));
    }

    function testComparingSameIntValues(){
        $comparator = $this->stringComparatorFactory();
        Assert::isTrue($comparator->areEqual(4, 4));
    }

    function testComparingDifferentIntValues(){
        $comparator = $this->stringComparatorFactory();
        Assert::isFalse($comparator->areEqual(5, 4));
    }

    function testComparingSameStringAndNullValues(){
        $comparator = $this->stringComparatorFactory();
        Assert::isTrue($comparator->areEqual(null, ""));
    }

    function testOneFilterEqualValues(){
        $comparator = $this->stringComparatorFactory();

        $firstColumn = " hi";
        $secondColumn = " h i ";

        $removeSpacesFilter = new RemoveSpacesMockFilter();
        $comparator->addFilter($removeSpacesFilter);

        Assert::isTrue($comparator->areEqual($firstColumn, $secondColumn));
    }

    function testOneFilterDifferentValues(){
        $comparator = $this->stringComparatorFactory();

        $firstColumn = " hi";
        $secondColumn = " hello";

        $removeSpacesFilter = new RemoveSpacesMockFilter();
        $comparator->addFilter($removeSpacesFilter);

        Assert::isFalse($comparator->areEqual($firstColumn, $secondColumn));
    }

    function testTwoFiltersInOrderEqualValues(){
        $comparator = $this->stringComparatorFactory();

        $firstColumn = " Hi";
        $secondColumn = " h I ";

        $comparator->addFilter(new RemoveSpacesMockFilter());
        $comparator->addFilter(new LowercaseMockFilter());

        Assert::isTrue($comparator->areEqual($firstColumn, $secondColumn));
    }
}