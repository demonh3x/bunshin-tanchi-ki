<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/SurnameFilter.php");

class TestSurnameFilter extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createFilter(){
        return Core::getCodeCoverageWrapper("SurnameFilter");
    }

    private function assertExpected($expected, $input){
        $filter = $this->createFilter();
        Assert::areIdentical($expected, $filter->applyTo($input));
    }

    function testIfAllUppercaseShouldCapitalizeFirstLetter(){
        $this->assertExpected("Marie", "MARIE");
        $this->assertExpected("MaRIe", "MaRIe");
    }

    function testIfAllLowercaseShouldCapitalizeFirstLetter(){
        $this->assertExpected("Marie", "marie");
        $this->assertExpected("maRie", "maRie");
    }

    function testSpacesAndHyphensRemainUnaltered(){
        $this->assertExpected("Marie Charlotte", "Marie Charlotte");
        $this->assertExpected("Marie-Charlotte", "Marie-Charlotte");
    }
}