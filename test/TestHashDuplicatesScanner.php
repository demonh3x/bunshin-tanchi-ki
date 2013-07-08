<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashDuplicatesScanner.php");
include_once(__ROOT_DIR__ . "src/HashCalculators/StringHashCalculator.php");

include_once(__ROOT_DIR__ . "test/mocks/NotReadyMockReader.php");
include_once(__ROOT_DIR__ . "test/mocks/MockReader.php");
include_once(__ROOT_DIR__ . "test/mocks/LowercaseMockFilter.php");

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

        $reader = new MockReader();
        $reader->setResource($readerData);
        $scanner->setReader($reader);

        return $scanner;
    }

    function testRaiseExceptionWhenGettingUniquesAndHashCalculatorNotSet(){
        $scanner = $this->createScannerWithReader(array());
        $exceptionRaised = false;

        try {
            $scanner->getUniques();
        } catch(\Exception $e){
            $exceptionRaised = true;
        }

        Assert::isTrue($exceptionRaised);
    }

    function testRaiseExceptionWhenGettingDuplicatesAndHashCalculatorNotSet(){
        $scanner = $this->createScannerWithReader(array());
        $exceptionRaised = false;

        try {
            $scanner->getDuplicates();
        } catch(\Exception $e){
            $exceptionRaised = true;
        }

        Assert::isTrue($exceptionRaised);
    }

    private function createScannerWithReaderAndHashCalculator($readerData){
        $scanner = $this->createScanner();

        $reader = new MockReader();
        $reader->setResource($readerData);
        $scanner->setReader($reader);

        $hashCalculator = new \StringHashCalculator();
        $scanner->setHashCalculator($hashCalculator);

        return $scanner;
    }

    function testGettingUniquesWhenNoDuplicates(){
        $dataOneColumnNoDuplicates = array(
            array(
                "Column1" => "Foo"
            ),
            array(
                "Column1" => "Bar"
            )
        );

        $scanner = $this->createScannerWithReaderAndHashCalculator($dataOneColumnNoDuplicates);
        Assert::areIdentical($dataOneColumnNoDuplicates, $scanner->getUniques());
    }

    function testGettingUniquesWhenNoDuplicatesAllColumns(){
        $dataOneColumnNoDuplicates = array(
            array(
                "Column1" => "Foo", "Column2" => "asdf", "Column3" => "qwer"
            ),
            array(
                "Column1" => "Bar", "Column2" => "asdf", "Column3" => "qwer"
            )
        );

        $scanner = $this->createScannerWithReaderAndHashCalculator($dataOneColumnNoDuplicates);
        Assert::areIdentical($dataOneColumnNoDuplicates, $scanner->getUniques());
    }

    function testGettingUniquesWhenDuplicates(){
        $dataOneColumnWithDuplicates = array(
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

        $scanner = $this->createScannerWithReaderAndHashCalculator($dataOneColumnWithDuplicates);

        $uniques = array(
            array(
                "Column1" => "Foo"
            ),

        );
        Assert::areIdentical($uniques, $scanner->getUniques());
    }

    function testGettingUniquesWhenDuplicatesAllColumns(){
        $dataOneColumnWithDuplicates = array(
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

        $scanner = $this->createScannerWithReaderAndHashCalculator($dataOneColumnWithDuplicates);

        $uniques = array(
            array(
                "Column1" => "Foo", "Column2" => "asdf", "Column3" => "qwer"
            ),

        );
        Assert::areIdentical($uniques, $scanner->getUniques());
    }

    function testGettingDuplicatesWhenNoDuplicates(){
        $dataOneColumnNoDuplicates = array(
            array(
                "Column1" => "Foo"
            ),
            array(
                "Column1" => "Bar"
            )
        );

        $scanner = $this->createScannerWithReaderAndHashCalculator($dataOneColumnNoDuplicates);
        Assert::areIdentical(array(), $scanner->getDuplicates());
    }

    function testGettingDuplicatesWhenTwoDuplicates(){
        $dataOneColumnWithDuplicates = array(
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

        $scanner = $this->createScannerWithReaderAndHashCalculator($dataOneColumnWithDuplicates);

        $duplicates = array(
            array(
                array(
                    "Column1" => "Bar"
                ),
                array(
                    "Column1" => "Bar"
                )
            )
        );
        Assert::areIdentical($duplicates, $scanner->getDuplicates());
    }

    function testGettingDuplicatesWhenFourDuplicates(){
        $dataOneColumnWithDuplicates = array(
            array(
                "Column1" => "Foo"
            ),
            array(
                "Column1" => "Bar"
            ),
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

        $scanner = $this->createScannerWithReaderAndHashCalculator($dataOneColumnWithDuplicates);

        $duplicates = array(
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
                array(
                    "Column1" => "Bar"
                )
            )
        );
        Assert::areIdentical($duplicates, $scanner->getDuplicates());
    }
}