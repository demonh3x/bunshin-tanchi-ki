<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashDuplicatesExporter.php");
include_once(__ROOT_DIR__ . "src/HashCalculators/StringHashCalculator.php");

include_once(__ROOT_DIR__ . "test/mocks/NotReadyMockReader.php");

include_once(__ROOT_DIR__ . "src/RandomReaders/RamRandomReader.php");
include_once(__ROOT_DIR__ . "src/Writers/RamWriter.php");

class TestHashDuplicatesExporter extends TestFixture{
    public function setUp(){
    }

    public function tearDown(){
    }

    private function createExporter(){
        return Core::getCodeCoverageWrapper('HashDuplicatesExporter');
    }

    function testRaiseExceptionWhenSettingAReaderNotReady(){
        $exporter = $this->createExporter();
        $exceptionRaised = false;

        try {
            $exporter->setReader(new NotReadyMockReader());
        } catch(\Exception $e){
            $exceptionRaised = true;
        }

        Assert::isTrue($exceptionRaised);
    }

    private function createExporterWithReader($readerData){
        $exporter = $this->createExporter();

        $ramId = "testHashDuplicatesExporter";
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
        $exporter->setReader($reader);

        return $exporter;
    }

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
    }

    private function createExporterrWithReaderAndHashCalculator($readerData){
        $exporter = $this->createExporterWithReader($readerData);

        $hashCalculator = new \StringHashCalculator();
        $exporter->setHashCalculator($hashCalculator);

        return $exporter;
    }

    function testNotRaisingExceptionWhenReaderAndHashCalculatorAreSet(){
        $exporter = $this->createExporterrWithReaderAndHashCalculator(array());
        $exporter->scan();
    }

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
        $exporter = $this->createExporterrWithReaderAndHashCalculator($input);

        $ramId = "testHashDuplicatesExporterAssertUniques";
        unset($GLOBALS[$ramId]);

        $exporter->setUniquesWriter($this->getRamWriter($ramId));
        $exporter->scan();

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
        $exporter = $this->createExporterrWithReaderAndHashCalculator($input);

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
    }
}

class MockRamWriterFactory implements \WriterFactory{
    public $createdWriters = array();

    function createWriter($id){
        $ramId = "testMockRamWriterFactory_$id";
        unset($GLOBALS[$ramId]);

        $writer = new \RamWriter();
        $writer->create($ramId);
        if (!$writer->isReady()){
            throw new \Exception("The MockRamWriterFactory couldn't create a Writer with the id: [$id]");
        }

        $this->createdWriters[$ramId] = &$writer;

        return $this->createdWriters[$ramId];
    }
}