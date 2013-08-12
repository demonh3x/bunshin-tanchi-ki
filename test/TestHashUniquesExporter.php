<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashUniquesExporter.php");
include_once(__ROOT_DIR__ . "src/HashCalculators/StringHashCalculator.php");
include_once(__ROOT_DIR__ . "src/HashList.php");
include_once(__ROOT_DIR__ . "src/HashCalculators/PerColumnRowFilter.php");

include_once(__ROOT_DIR__ . "test/mocks/NotReadyMockReader.php");
include_once(__ROOT_DIR__ . "test/mocks/MockRamWriterFactory.php");

include_once(__ROOT_DIR__ . "src/RandomReaders/RamRandomReader.php");
include_once(__ROOT_DIR__ . "src/Writers/RamWriter.php");

include_once("mocks/LowercaseMockFilter.php");

class TestHashUniquesExporter extends TestFixture{
    public function setUp(){
    }

    public function tearDown(){
    }

    private function createExporter(\HashCalculator $hashCalculator, \UniquesList $uniquesList, $randomReaders = array()){
        return Core::getCodeCoverageWrapper(
            'HashUniquesExporter',
            array($hashCalculator, $uniquesList, $randomReaders)
        );
    }

    private function createRamReader($ramId, $readerData){
        unset($GLOBALS[$ramId]);

        $writer = new \RamWriter($ramId);
        foreach ($readerData as $row){
            $writer->writeRow($row);
        }

        $reader = new \RamRandomReader($ramId);
        return $reader;
    }

    private function createDefaultExporter($readerData){
        $ramId = "testHashDuplicatesExporter";
        $reader = $this->createRamReader($ramId, $readerData);

        $exporter = $this->createExporter(new \StringHashCalculator(), new \HashList(), array($reader));

        return $exporter;
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
        $exporter = $this->createDefaultExporter($input);

        $ramId = "testHashDuplicatesExporterAssertUniques";
        unset($GLOBALS[$ramId]);

        $exporter->export($this->getRamWriter($ramId));

        $actualData = $this->readRamData($ramId);

        Assert::areIdentical($expectedOutput, $actualData);
    }

    private function getRamWriter($ramId){
        $writer = new \RamWriter($ramId);
        return $writer;
    }
    private function readRamData($ramId){
        $reader = new \RamRandomReader($ramId);

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
        $exporter = $this->createDefaultExporter($input);

        $factory = new MockRamWriterFactory();

        $exporter->export(new \NullWriter(), $factory);

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

        $duplicates = array(
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
        $this->assertDuplicates($input, $duplicates);
    }

    function testFilteringTheOutputUniquesData(){
        $input = array(
            array(
                "Column1" => "Foo", "Column2" => "bar", "Column3" => "Hi"
            )
        );

        $exporter = $this->createDefaultExporter($input);

        $ramId = "testFilteringTheOutputUniquesData";
        unset($GLOBALS[$ramId]);

        $rowFilter = new \PerColumnRowFilter(array(
            "Column1" => new LowercaseMockFilter()
        ));

        $exporter->export($this->getRamWriter($ramId), null, $rowFilter);

        $actualData = $this->readRamData($ramId);

        $uniquesOutput = array(
            array(
                "Column1" => "foo", "Column2" => "bar", "Column3" => "Hi"
            )
        );

        Assert::areIdentical($uniquesOutput, $actualData);
    }

    function testFilteringTheOutputDuplicatesData(){
        $input = array(
            array(
                "Column1" => "Foo", "Column2" => "bar"
            ),
            array(
                "Column1" => "Foo", "Column2" => "bar"
            ),
            array(
                "Column1" => "Bar", "Column2" => "bar"
            ),
            array(
                "Column1" => "Bar", "Column2" => "bar"
            ),
            array(
                "Column1" => "Hi", "Column2" => "bar"
            )
        );

        $expectedOutput = array(
            array(
                array(
                    "Column1" => "foo", "Column2" => "bar"
                ),
                array(
                    "Column1" => "foo", "Column2" => "bar"
                )
            ),
            array(
                array(
                    "Column1" => "bar", "Column2" => "bar"
                ),
                array(
                    "Column1" => "bar", "Column2" => "bar"
                )
            )
        );

        $exporter = $this->createDefaultExporter($input);

        $rowFilter = new \PerColumnRowFilter(array(
            "Column1" => new LowercaseMockFilter()
        ));

        $factory = new MockRamWriterFactory();

        $exporter->export(new \NullWriter(), $factory, $rowFilter);

        $allDuplicates = array();
        foreach ($factory->createdWriters as $id => $writer){
            $allDuplicates[] = $this->readRamData($id);
        }


        Assert::areIdentical($expectedOutput, $allDuplicates);
    }

    private function createMultipleReadersExporter($readers){
        $exporter = $this->createExporter(new \StringHashCalculator(), new \HashList(), $readers);

        return $exporter;
    }

    function testMultipleReaders(){
        $reader1 = $this->createRamReader(
            "testMultipleReaders1",
            array(
                array(
                    "Column1" => "Foo"
                ),
                array(
                    "Column1" => "Bar"
                ),
                array(
                    "Column1" => "Bar"
                )
            )
        );

        $reader2 = $this->createRamReader(
            "testMultipleReaders2",
            array(
                array(
                    "Column1" => "Foo"
                ),
                array(
                    "Column1" => "Foo"
                ),
                array(
                    "Column1" => "Bar"
                )
            )
        );

        $expectedOutput = array(
            array(
                array(
                    "Column1" => "Bar"
                ),
                array(
                    "Column1" => "Bar"
                ),
                array(
                    "Column1" => "Bar"
                ),
            ),
            array(
                array(
                    "Column1" => "Foo"
                ),
                array(
                    "Column1" => "Foo"
                ),
                array(
                    "Column1" => "Foo"
                ),
            )
        );

        $exporter = $this->createMultipleReadersExporter(array($reader1, $reader2));

        $factory = new MockRamWriterFactory();

        $exporter->export(new \NullWriter(), $factory);

        $allDuplicates = array();
        foreach ($factory->createdWriters as $id => $writer){
            $allDuplicates[] = $this->readRamData($id);
        }

        Assert::areIdentical($expectedOutput, $allDuplicates);
    }
}
