<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Filters/SubstituteAccentsFilter.php");

class TestSubstituteAccentsFilter extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    function testSubstituteAccents(){
        $filter = new \SubstituteAccentsFilter();
        $input = "áàäâªÁÀÂÄdoéèëêÉÈÊËreíìïîÍÌÏÎmióòöôÓÒÖÔfaúùüûÚÙÛÜsolñÑçÇlasi";
        $expected = "aaaaaAAAAdoeeeeEEEEreiiiiIIIImiooooOOOOfauuuuUUUUsolnNcClasi";
        Assert::areIdentical($expected, $filter->filter($input));
    }
}