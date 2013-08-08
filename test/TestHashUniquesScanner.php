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
            new \StringHashCalculator(),
            new \HashList()
        ));
    }
/*
    function testRaiseExceptionWhenSettingAReaderNotReady(){
        $scanner = $this->createScanner();
        $exceptionRaised = false;

        try {
            $scanner->addReader(new NotReadyMockReader());
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
        $scanner->addReader($reader);

        return $scanner;
    }

    private function createRamReader($data, $id){
        $writer = new \RamWriter($id);
        foreach ($data as $row){
            $writer->writeRow($row);
        }

        $reader = new \RamRandomReader($id);

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
        foreach ($results as $value) {
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

    function testGettingUniquesTwoInputs(){
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
        $scanner->addReader($this->createRamReader($inputB, "testGettingUniquesTwoInputsDataB"));

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
    }

    function testReceivingDuplicates(){
        $inputA = array(
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

        $inputB = array(
            array(
                "Column1" => "Foo"
            ),
            array(
                "Column1" => "Asdf"
            )
        );

        $scanner = $this->createDefaultScanner($inputA);
        $scanner->addReader($this->createRamReader($inputB, "testReceivingDuplicatesDataB"));

        $duplicatesListener = new MockRowListener();

        $scanner->setDuplicatesListener($duplicatesListener);
        $scanner->getUniques();

        $expected = array(
            array(
                "Column1" => "Bar"
            ),
            array(
                "Column1" => "Bar"
            ),
            array(
                "Column1" => "Foo"
            ),
            array(
                "Column1" => "Foo"
            )
        );
        $actual = $duplicatesListener->duplicates;

        Assert::areIdentical($expected, $actual);
    }
}

include_once(__ROOT_DIR__ . "src/RowListener.php");
class MockRowListener implements \RowListener{
    public $duplicates = array();

    function receiveRow(\Row $row) {
        $this->duplicates[] = $row->getData();
    }
}
