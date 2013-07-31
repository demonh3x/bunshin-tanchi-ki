<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/CellGenerators/UniquePURLGenerator.php");

class TestUniquePURLGenerator extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createGenerator($usedPurls = array()){
        return Core::getCodeCoverageWrapper("UniquePURLGenerator", array("2", "3", "1", "4", $usedPurls));
    }

    private $testRow = array(
        "0" => "",
        "1" => "Ms",
        "2" => "Marie",
        "3" => "Charlotte",
        "4" => "PURLGoingToBeOverwrited",
    );

    private $purlSuccession = array(
        "MarieCharlotte",
        "MarieC",
        "MCharlotte",
        "MsMarieCharlotte",
        "MsMarieC",
        "MsMCharlotte",
    );

    function testGenerateOneCell(){
        $expected = array(
            "0" => "",
            "1" => "Ms",
            "2" => "Marie",
            "3" => "Charlotte",
            "4" => "MarieCharlotte",
        );

        $generator = $this->createGenerator();
        Assert::areIdentical($expected, $generator->generate($this->testRow));
    }

    function testFirstCombinationUsed(){
        $expected = array(
            "0" => "",
            "1" => "Ms",
            "2" => "Marie",
            "3" => "Charlotte",
            "4" => "MarieC",
        );

        $generator = $this->createGenerator(array("MarieCharlotte"));
        Assert::areIdentical($expected, $generator->generate($this->testRow));
    }

    function testSuccessionOfGeneratedPurls(){
        for ($purlIndex = 0; $purlIndex < count($this->purlSuccession); $purlIndex++){
            $generator = $this->createGenerator();

            for ($i = 0; $i < $purlIndex; $i++){
                $generator->generate($this->testRow);
            }

            $expectedPurl = $this->purlSuccession[$purlIndex];
            $actualPurl = $generator->generate($this->testRow)["4"];

            Assert::areIdentical($expectedPurl, $actualPurl);
        }
    }

    function testFailingToGeneratePurl(){
        $generator = $this->createGenerator($this->purlSuccession);

        $exceptionTrown = false;

        try {
            $generator->generate($this->testRow);
        }catch (\Exception $e){
            $exceptionTrown = true;
        }

        Assert::isTrue($exceptionTrown);
    }

    function testCleaningSurname(){
        $input = array(
            "0" => "",
            "1" => "Ms",
            "2" => "Marie",
            "3" => "Cha'r Lót-te",
            "4" => "PURLGoingToBeOverwrited",
        );

        $expected = array(
            "0" => "",
            "1" => "Ms",
            "2" => "Marie",
            "3" => "Cha'r Lót-te",
            "4" => "MarieCharLotte",
        );

        $generator = $this->createGenerator();
        $actual = $generator->generate($input);
        Assert::areIdentical($expected, $actual);
    }

    function testCleaningFirstname(){
        $input = array(
            "0" => "",
            "1" => "MR",
            "2" => "Jason Mark",
            "3" => "Smith",
            "4" => "PURLGoingToBeOverwrited",
        );

        $expected = array(
            "0" => "",
            "1" => "MR",
            "2" => "Jason Mark",
            "3" => "Smith",
            "4" => "JasonSmith",
        );

        $generator = $this->createGenerator();
        $actual = $generator->generate($input);
        Assert::areIdentical($expected, $actual);
    }

    function testCleaningSalutation(){
        $input = array(
            "0" => "",
            "1" => "MR",
            "2" => "Jason",
            "3" => "Smith",
            "4" => "PURLGoingToBeOverwrited",
        );

        $expected = array(
            "0" => "",
            "1" => "MR",
            "2" => "Jason",
            "3" => "Smith",
            "4" => "MrJasonSmith",
        );

        $generator = $this->createGenerator(array("JasonSmith", "JasonS", "JSmith"));
        $actual = $generator->generate($input);
        Assert::areIdentical($expected, $actual);
    }


}