<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashUniquesScanner.php");
include_once(__ROOT_DIR__ . "src/HashCalculators/StringHashCalculator.php");

include_once(__ROOT_DIR__ . "src/RandomReaders/RamRandomReader.php");

include_once(__ROOT_DIR__ . "src/Writers/RamWriter.php");


class TestHashUniquesScanner extends TestFixture{
    public function setUp(){
    }

    public function tearDown(){
    }

    private function createScanner(){
        return Core::getCodeCoverageWrapper('HashUniquesScanner', array(
            new \StringHashCalculator()
        ));
    }
/*
    function testRaiseExceptionWhenSettingAReaderNotReady(){
        $scanner = $this->createScanner();
        $exceptionRaised = false;

        try {
            $scanner->setReader(new NotReadyMockReader());
        } catch(\Exception $e){
            $exceptionRaised = true;
        }

        Assert::isTrue($exceptionRaised);
    }*/

    function testGetUniquesNoReaders(){
        $scanner = $this->createScanner();

        $actualData = $this->getResultsArray($scanner->getUniques());

        Assert::areIdentical(array(), $actualData);
    }

    private function createDefaultScanner($readerData){
        $scanner = $this->createScanner();

        $ramId = "testHashUniquesScanner";
        unset($GLOBALS[$ramId]);

        $reader = $this->createRamReader($readerData, $ramId);
        $scanner->setReader($reader);

        return $scanner;
    }

    private function createRamReader($data, $id){
        $writer = new \RamWriter();
        $writer->create($id);
        if (!$writer->isReady()){
            throw new \Exception ("Can't create writer!");
        }
        foreach ($data as $row){
            $writer->writeRow($row);
        }

        $reader = new \RamRandomReader();
        $reader->open($id);

        return $reader;
    }

/*
    function testRaiseExceptionWhenScanningAndHashCalculatorNotSet(){
        $exporter = $this->createExporterWithReader(array(
            "asdf"
        ));
        $exceptionRaised = false;

        try {
            $exporter->scan();
        } catch(\Exception $e){
            $exceptionRaised = true;
        }

        Assert::isTrue($exceptionRaised);
    }*/

/*
    function testNotRaisingExceptionWhenReaderAndHashCalculatorAreSet(){
        $exporter = $this->createExporterrWithReaderAndHashCalculator(array());
        $exporter->scan();
    }*/
/*
    function testRaiseExceptionWhenSettingANotReadyUniquesWriter(){
        $exporter = $this->createExporterrWithReaderAndHashCalculator(array());

        $exceptionRaised = false;

        $writer = new \RamWriter();
        try {
            $exporter->setUniquesWriter($writer);
        } catch(\Exception $e){
            $exceptionRaised = true;
        }

        Assert::isTrue($exceptionRaised);
    }*/

    function testGetUniquesImplementsIterator(){
        $input = array();
        $scanner = $this->createDefaultScanner($input);

        $results = $scanner->getUniques();

        Assert::isTrue($results instanceof \Iterator);
    }

    function testGetUniquesNoDupsOneColumn(){
        $input = array(
            array(
                "Column1" => "Foo"
            ),
            array(
                "Column1" => "Bar"
            ),
            array(
                "Column1" => "Asdf"
            )
        );
        $this->assertUniques($input, $input);
    }

    private function assertUniques($input, $expectedOutput){
        $scanner = $this->createDefaultScanner($input);

        $results = $scanner->getUniques();
        $actualData = $this->getResultsArray($results);

        Assert::areIdentical($expectedOutput, $actualData);
    }

    private function getResultsArray($results){
        $actualData = array();
        foreach ($results as $key => $value) {
            $actualData[] = $value;
        }
        return $actualData;
    }

    function testGetUniquesNoDupsThreeColumns(){
        $input = array(
            array(
                "Column1" => "Foo", "Column2" => "asdf", "Column3" => "qwer"
            ),
            array(
                "Column1" => "Bar", "Column2" => "asdf", "Column3" => "qwer"
            )
        );
        $this->assertUniques($input, $input);
    }

    function testGetUniquesWhenDupsOneColumn(){
        $input = array(
            array(
                "Column1" => "Foo"
            ),
            array(
                "Column1" => "Bar"
            ),
            array(
                "Column1" => "Bar"
            )
        );

        $uniques = array(
            array(
                "Column1" => "Foo"
            )
        );

        $this->assertUniques($input, $uniques);
    }

    function testGetUniquesWhenDupsThreeColumns(){
        $input = array(
            array(
                "Column1" => "Foo", "Column2" => "asdf", "Column3" => "qwer"
            ),
            array(
                "Column1" => "Bar", "Column2" => "asdf", "Column3" => "qwer"
            ),
            array(
                "Column1" => "Bar", "Column2" => "asdf", "Column3" => "qwer"
            )
        );

        $uniques = array(
            array(
                "Column1" => "Foo", "Column2" => "asdf", "Column3" => "qwer"
            )
        );
        $this->assertUniques($input, $uniques);
    }

    function testGetUniquesWhenAllDupsOneColumn(){
        $input = array(
            array(
                "Column1" => "Bar"
            ),
            array(
                "Column1" => "Bar"
            ),
            array(
                "Column1" => "Bar"
            )
        );

        $uniques = array();

        $this->assertUniques($input, $uniques);
    }

/*    function testGettingUniquesTwoInputs(){
        $inputA = array(
            array(
                "Column1" => "Foo"
            ),
            array(
                "Column1" => "Bar"
            ),
            array(
                "Column1" => "Asdf"
            )
        );

        $inputB = array(
            array(
                "Column1" => "Foo"
            ),
            array(
                "Column1" => "Qwer"
            )
        );

        $scanner = $this->createDefaultScanner($inputA);
        $scanner->setReader($this->createRamReader($inputB, "testGettingUniquesTwoInputsDataB"));

        $expected = array(
            array(
                "Column1" => "Bar"
            ),
            array(
                "Column1" => "Asdf"
            ),
            array(
                "Column1" => "Qwer"
            )
        );
        $actual = $this->getResultsArray($scanner->getUniques());

        Assert::areIdentical($expected, $actual);
    }*/
/*
    function testGettingDuplicatesWhenNoDuplicatesOneColumn(){
        $input = array(
            array(
                "Column1" => "Foo"
            ),
            array(
                "Column1" => "Bar"
            ),
            array(
                "Column1" => "Asdf"
            )
        );

        $expected = array();

        $this->assertDuplicates($input, $expected);
    }

    function testGettingDuplicatesWhenTwoDuplicatesOneColumn(){
        $input = array(
            array(
                "Column1" => "Foo"
            ),
            array(
                "Column1" => "Bar"
            ),
            array(
                "Column1" => "Bar"
            )
        );

        $expected = array(
            array(
                array(
                    "Column1" => "Bar"
                ),
                array(
                    "Column1" => "Bar"
                )
            )
        );

        $this->assertDuplicates($input, $expected);
    }

    private function assertDuplicates($input, $expectedOutput){
        $exporter = $this->createScannerWithReaderAndHashCalculator($input);

        $factory = new MockRamWriterFactory();

        $exporter->setDuplicatesWriterFactory($factory);
        $exporter->scan();

        $allDuplicates = array();
        foreach ($factory->createdWriters as $id => $writer){
            $allDuplicates[] = $this->readRamData($id);
        }

        Assert::areIdentical($expectedOutput, $allDuplicates);
    }

    function testGettingDuplicatesWhenDuplicatesThreeColumns(){
        $input = array(
            array(
                "Column1" => "Foo", "Column2" => "asdf", "Column3" => "qwer"
            ),
            array(
                "Column1" => "Foo", "Column2" => "asdf", "Column3" => "qwer"
            ),
            array(
                "Column1" => "Bar", "Column2" => "asdf", "Column3" => "qwer"
            ),
            array(
                "Column1" => "Bar", "Column2" => "asdf", "Column3" => "qwer"
            ),
            array(
                "Column1" => "Asdf", "Column2" => "asdf", "Column3" => "qwer"
            )
        );

        $uniques = array(
            array(
                array(
                    "Column1" => "Foo", "Column2" => "asdf", "Column3" => "qwer"
                ),
                array(
                    "Column1" => "Foo", "Column2" => "asdf", "Column3" => "qwer"
                )
            ),
            array(
                array(
                    "Column1" => "Bar", "Column2" => "asdf", "Column3" => "qwer"
                ),
                array(
                    "Column1" => "Bar", "Column2" => "asdf", "Column3" => "qwer"
                )
            )
        );
        $this->assertDuplicates($input, $uniques);
    }*/


}
