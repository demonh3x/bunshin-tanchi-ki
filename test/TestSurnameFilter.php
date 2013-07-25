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
    }

    function testIfAllLowercaseShouldCapitalizeFirstLetter(){
        $this->assertExpected("Marie", "marie");
    }

    function testIfNotAllUppercaseNorLowercaseLeaveItAsItIs(){
        $this->assertExpected("MaRIe", "MaRIe");
        $this->assertExpected("maRie", "maRie");
    }

    function testIfAllUppercaseWithSpacesShouldCapitalizeFirstLetter(){
        $this->assertExpected("Jamie van-macdow", "JAMIE VAN-MACDOW");
    }

    function testIfAllLowercaseWithSpacesShouldCapitalizeFirstLetter(){
        $this->assertExpected("Jamie van-macdow", "jamie van-macdow");
    }

    function testIfNotAllUppercaseWithSpacesLeaveItAsItIs(){
        $this->assertExpected("JAmie VAN-MacDow", "JAmie VAN-MacDow");
    }

    function testAccentsAndSpecialCharactersRemainUnaltered(){
        $this->assertExpected("Áarön'", "áarön'");
        $this->assertExpected("Áarön'", "ÁARÖN'");
    }
}