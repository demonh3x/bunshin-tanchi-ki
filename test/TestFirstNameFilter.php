<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/FirstNameFilter.php");

class TestFirstNameFilter extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createFilter(){
        return Core::getCodeCoverageWrapper("FirstNameFilter");
    }

    private function assertExpected($expected, $input){
        $filter = $this->createFilter();
        Assert::areIdentical($expected, $filter->applyTo($input));
    }

    function testCannotHaveAnySpaces(){
        $this->assertExpected("Marie", "Marie Charlotte");
    }

    function testStartWithACapitalLetter(){
        $this->assertExpected("Marie", "marie");
    }

    function testAfterTheFirstCapitalLetterMustBeAllLowercase(){
        $this->assertExpected("Marie", "MARiE");
    }

    function testFollowingLetterOfAHyphenCouldBeUpperOrLowercase(){
        $this->assertExpected("Marie-Charlotte", "Marie-Charlotte");
        $this->assertExpected("Marie-charlotte", "Marie-charlotte");
    }

    function testAllTheLettersFollowingTheFirstLetterAfterTheHyphenMustBeLowercase(){
        $this->assertExpected("Marie-Charlotte", "MarIe-CharLOtte");
        $this->assertExpected("Marie-charlotte", "MarIe-charLOtte");
    }

    function testAccentedCharactersAreAllowed(){
        $this->assertExpected("Márïe-Chàrlôtte", "Márïe-Chàrlôtte");
    }
}