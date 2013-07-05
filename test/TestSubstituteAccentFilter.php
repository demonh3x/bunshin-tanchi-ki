<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Comparators/Filters/SubstituteAccentsFilter.php");

class TestSubstituteAccentsFilter extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function substituteAccentsFilterFactory(){
        return Core::getCodeCoverageWrapper("SubstituteAccentsFilter");
    }

    function testUnchangedNormalSymbols(){
        $filter = $this->substituteAccentsFilterFactory();
        $input = "abcdfghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ~¨`";
        Assert::areIdentical($input, $filter->filter($input));
    }

    function testSubstituteAccents(){
        $filter = $this->substituteAccentsFilterFactory();
        $input = "áàäâªÁÀÂÄdoéèëêÉÈÊËreíìïîÍÌÏÎmióòöôÓÒÖÔfaúùüûÚÙÛÜsolñÑçÇlasi";
        $expected = "aaaaaAAAAdoeeeeEEEEreiiiiIIIImiooooOOOOfauuuuUUUUsolnNcClasi";
        Assert::areIdentical($expected, $filter->filter($input));
    }
}