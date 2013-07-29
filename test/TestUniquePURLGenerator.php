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

}