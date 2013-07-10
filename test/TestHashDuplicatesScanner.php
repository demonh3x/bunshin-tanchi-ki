<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashDuplicatesScanner.php");
include_once(__ROOT_DIR__ . "src/HashCalculators/StringHashCalculator.php");

include_once(__ROOT_DIR__ . "test/mocks/NotReadyMockReader.php");

include_once(__ROOT_DIR__ . "src/RandomReaders/RamRandomReader.php");
include_once(__ROOT_DIR__ . "src/Writers/RamWriter.php");

class TestHashDuplicatesScanner extends TestFixture{
    public function setUp(){
    }

    public function tearDown(){
    }

    private function createScanner(){
        return Core::getCodeCoverageWrapper('HashDuplicatesScanner');
    }

    function testRaiseExceptionWhenSettingAReaderNotReady(){
        $scanner = $this->createScanner();
        $exceptionRaised = false;

        try {
            $scanner->setReader(new NotReadyMockReader());
        } catch(\Exception $e){
            $exceptionRaised = true;
        }

        Assert::isTrue($exceptionRaised);
    }

    private function createScannerWithReader($readerData){
        $scanner = $this->createScanner();

        $ramId = "testHashDuplicatesScanner";
        unset($GLOBALS[$ramId]);

        $writer = new \RamWriter();
        $writer->create($ramId);
        if (!$writer->isReady()){
            throw new \Exception ("Can't create writer to set the default reader data!");
        }
        foreach ($readerData as $row){
            $writer->writeRow($row);
        }

        $reader = new \RamRandomReader();
        $reader->open($ramId);
        $scanner->setReader($reader);

        return $scanner;
    }

    function testRaiseExceptionWhenScanningAndHashCalculatorNotSet(){
        $scanner = $this->createScannerWithReader(array());
        $exceptionRaised = false;

        try {
            $scanner->scan();
        } catch(\Exception $e){
            $exceptionRaised = true;
        }

        Assert::isTrue($exceptionRaised);
    }

    private function createScannerWithReaderAndHashCalculator($readerData){
        $scanner = $this->createScannerWithReader($readerData);

        $hashCalculator = new \StringHashCalculator();
        $scanner->setHashCalculator($hashCalculator);

        return $scanner;
    }

    function testNotRaisingExceptionWhenReaderAndHashCalculatorAreSet(){
        $scanner = $this->createScannerWithReaderAndHashCalculator(array());
        $scanner->scan();
    }

    function testRaiseExceptionWhenSettingANotReadyUniquesWriter(){
        $scanner = $this->createScannerWithReaderAndHashCalculator(array());

        $exceptionRaised = false;

        $writer = new \RamWriter();
        try {
            $scanner->setUniquesWriter($writer);
        } catch(\Exception $e){
            $exceptionRaised = true;
        }

        Assert::isTrue($exceptionRaised);
    }

    function testScanningWithUniquesWriterWhenNoDuplicatesOneColumn(){
        $input = array(
            array(
                "Column1" => "Foo"
            ),
            array(
                "Column1" => "Bar"
            )
        );
        $this->assertUniques($input, $input);
    }

    private function assertUniques($input, $expectedOutput){
        $scanner = $this->createScannerWithReaderAndHashCalculator($input);

        $ramId = "testHashDuplicatesScannerAssertUniques";
        unset($GLOBALS[$ramId]);

        $scanner->setUniquesWriter($this->getRamWriter($ramId));
        $scanner->scan();

        $actualData = $this->readRamData($ramId);

        Assert::areIdentical($expectedOutput, $actualData);
    }

    private function getRamWriter($ramId){
        $writer = new \RamWriter();
        $writer->create($ramId);
        return $writer;
    }
    private function readRamData($ramId){
        $reader = new \RamRandomReader();
        $reader->open($ramId);

        $data = array();
        for ($i = 0; $i < $reader->getRowCount(); $i++){
            $data[$i] = $reader->readRow($i);
        }

        return $data;
    }

    function testGettingUniquesWhenNoDuplicatesThreeColumns(){
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

    function testGettingUniquesWhenDuplicatesOneColumn(){
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
            ),

        );

        $this->assertUniques($input, $uniques);
    }

    function testGettingUniquesWhenDuplicatesThreeColumns(){
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
            ),

        );
        $this->assertUniques($input, $uniques);
    }

    function testGettingUniquesWhenNoDuplicatesOneColumn(){
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

    function testGettingDuplicatesWhenNoDuplicates(){
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
        $scanner = $this->createScannerWithReaderAndHashCalculator($input);

        $factory = new MockRamWriterFactory();

        $scanner->setDuplicatesWriterFactory($factory);
        $scanner->scan();

        $allDuplicates = array();
        foreach ($factory->createdWriters as $id => $writer){
            $allDuplicates[] = $this->readRamData($id);
        }

        Assert::areIdentical($expectedOutput, $allDuplicates);
    }
/*
    function testGettingDuplicatesWhenTwoDuplicates(){
        Assert::fail();
    }

    function testGettingDuplicatesWhenFourDuplicates(){
        Assert::fail();
    }*/
}

class MockRamWriterFactory implements \WriterFactory{
    public $createdWriters = array();

    function createWriter($id){
        $writer = new \RamWriter();
        $ramId = "testMockRamWriterFactory_$id";
        $writer->create($ramId);
        if (!$writer->isReady()){
            throw new \Exception("The MockRamWriterFactory couldn't create a Writer with the id: [$id]");
        }
        $this->createdWriters[$ramId] = &$writer;

        return $this->createdWriters[$ramId];
    }
}