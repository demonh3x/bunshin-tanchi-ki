<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/CellGenerators/UniquePURLGenerator.php");

define("SALUTATION", "1");
define("FIRSTNAME", "2");
define("SURTNAME", "3");
define("PURL", "4");

class TestUniquePURLGenerator extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createGenerator($usedPurls = array()){
        return Core::getCodeCoverageWrapper("UniquePURLGenerator",
            array(FIRSTNAME, SURTNAME, SALUTATION, PURL, $usedPurls)
        );
    }

    private $testRow = array(
        "0" => "",
        SALUTATION => "Mr",
        FIRSTNAME => "Jamie",
        SURTNAME => "MacDow",
        PURL => "PURLGoingToBeOverwrited",
    );

    private $purlSuccession = array(
        "JamieMacDow",
        "JamieM",
        "JMacDow",
        "MrJamieMacDow",
        "Jamie-MacDow",
        "Jamie-M",
        "J-MacDow",
        "MrJamieM",
        "MrJMacDow",
        "MrJamie-MacDow",
        "Mr-JamieMacDow",
        "Mr-Jamie-MacDow",
        "MrJamie-M",
        "Mr-JamieM",
        "Mr-Jamie-M",
        "MrJ-MacDow",
        "Mr-JMacDow",
        "Mr-J-MacDow",
        "MacDowJamie",
        "MacDowJ",
        "MacDow-Jamie",
        "MacDow-J",
        "MrMacDowJamie",
        "MrMacDow-Jamie",
        "Mr-MacDowJamie",
        "Mr-MacDow-Jamie",
        "MrMacDowJ",
        "Mr-MacDowJ",
        "MrMacDow-J",
        "Mr-MacDow-J",
    );

    function testGenerateOneCell(){
        $expected = array(
            "0" => "",
            SALUTATION => "Mr",
            FIRSTNAME => "Jamie",
            SURTNAME => "MacDow",
            PURL => "JamieMacDow",
        );

        $generator = $this->createGenerator();
        Assert::areIdentical($expected, $generator->generate($this->testRow));
    }

    function testFirstCombinationUsed(){
        $expected = array(
            "0" => "",
            SALUTATION => "Mr",
            FIRSTNAME => "Jamie",
            SURTNAME => "MacDow",
            PURL => "JamieM",
        );

        $generator = $this->createGenerator(array("JamieMacDow"));
        Assert::areIdentical($expected, $generator->generate($this->testRow));
    }

    private function assertSuccession($inputRow = array(), $purlSuccession = array()){
        for ($purlIndex = 0; $purlIndex < count($purlSuccession); $purlIndex++){
            $usedPurls = array_slice($purlSuccession, 0, $purlIndex);
            $generator = $this->createGenerator($usedPurls);

            $expectedPurl = $purlSuccession[$purlIndex];
            $actualPurl = $generator->generate($inputRow)[PURL];

            Assert::areIdentical($expectedPurl, $actualPurl);
        }
    }

    function testSuccessionOfGeneratedPurls(){
        $this->assertSuccession($this->testRow, $this->purlSuccession);
    }

    private function assertExceptionWhenSuccessionEnds($inputRow = array(), $purlSuccession = array()){
        $generator = $this->createGenerator($purlSuccession);

        $exceptionTrown = false;

        try {
            $generator->generate($inputRow);
        }catch (\Exception $e){
            $exceptionTrown = true;
        }

        Assert::isTrue($exceptionTrown);
    }

    function testFailingToGeneratePurlThrowsAnException(){
        $this->assertExceptionWhenSuccessionEnds($this->testRow, $this->purlSuccession);
    }

    function testCleaningSurname(){
        $input = array(
            "0" => "",
            SALUTATION => "Mr",
            FIRSTNAME => "Jamie",
            SURTNAME => "Ma'c Dó-w",
            PURL => "PURLGoingToBeOverwrited",
        );

        $expected = array(
            "0" => "",
            SALUTATION => "Mr",
            FIRSTNAME => "Jamie",
            SURTNAME => "Ma'c Dó-w",
            PURL => "JamieMacDow",
        );

        $generator = $this->createGenerator();
        $actual = $generator->generate($input);
        Assert::areIdentical($expected, $actual);
    }

    function testCleaningFirstname(){
        $input = array(
            "0" => "",
            SALUTATION => "MR",
            FIRSTNAME => "Ja'mí-e Já-son",
            SURTNAME => "MacDow",
            PURL => "PURLGoingToBeOverwrited",
        );

        $expected = array(
            "0" => "",
            SALUTATION => "MR",
            FIRSTNAME => "Ja'mí-e Já-son",
            SURTNAME => "MacDow",
            PURL => "JamieMacDow",
        );

        $generator = $this->createGenerator();
        $actual = $generator->generate($input);
        Assert::areIdentical($expected, $actual);
    }

    function testCleaningSalutation(){
        $input = array(
            "0" => "",
            SALUTATION => "MR",
            FIRSTNAME => "Jamie",
            SURTNAME => "MacDow",
            PURL => "PURLGoingToBeOverwrited",
        );

        $expected = array(
            "0" => "",
            SALUTATION => "MR",
            FIRSTNAME => "Jamie",
            SURTNAME => "MacDow",
            PURL => "MrJamieMacDow",
        );

        $generator = $this->createGenerator(array("JamieMacDow", "JamieM", "JMacDow"));
        $actual = $generator->generate($input);
        Assert::areIdentical($expected, $actual);
    }

    function testHyphenSeparatedPurlWithHyphensInSurname(){
        $input = array(
            "0" => "",
            SALUTATION => "Mr",
            FIRSTNAME => "Jamie",
            SURTNAME => "Mac-Dow",
            PURL => "PURLGoingToBeOverwrited",
        );

        $expected = array(
            "0" => "",
            SALUTATION => "Mr",
            FIRSTNAME => "Jamie",
            SURTNAME => "Mac-Dow",
            PURL => "Jamie-MacDow",
        );

        $usedPurls = array("JamieMacDow", "JamieM", "JMacDow", "MrJamieMacDow", "MrJamieM", "MrJMacDow");

        $generator = $this->createGenerator($usedPurls);
        $actual = $generator->generate($input);
        Assert::areIdentical($expected, $actual);
    }


    private $testRowNoSalutation = array(
        "0" => "",
        SALUTATION => "",
        FIRSTNAME => "Jamie",
        SURTNAME => "Mac-Dow",
        PURL => "PURLGoingToBeOverwrited",
    );

    private $purlSuccessionWithoutSalutation = array(
        "JamieMacDow",
        "JamieM",
        "JMacDow",
        "Jamie-MacDow",
        "Jamie-M",
        "J-MacDow",
        "MacDowJamie",
        "MacDowJ",
        "MacDow-Jamie",
        "MacDow-J",
    );

    function testNotDefinedSalutation() {
        $this->assertSuccession($this->testRowNoSalutation, $this->purlSuccessionWithoutSalutation);
    }

    function testFailingToGeneratePurlWhenNoSalutationThrowsException(){
        $this->assertExceptionWhenSuccessionEnds($this->testRowNoSalutation, $this->purlSuccessionWithoutSalutation);
    }
}