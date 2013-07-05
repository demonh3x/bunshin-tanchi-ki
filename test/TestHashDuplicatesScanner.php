<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashDuplicatesScanner.php");

include_once(__ROOT_DIR__ . "test/mocks/NotReadyMockReader.php");
include_once(__ROOT_DIR__ . "test/mocks/MockReader.php");

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

    function testGettingUniquesWhenNoDuplicates(){
        $dataOneColumnNoDuplicates = array(
            array(
                "Column1" => "Foo"
            ),
            array(
                "Column1" => "Bar"
            )
        );

        $scanner = $this->createScannerWithReader($dataOneColumnNoDuplicates);
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

        $scanner = $this->createScannerWithReader($dataOneColumnWithDuplicates);

        $uniques = array(
            array(
                "Column1" => "Foo"
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

        $scanner = $this->createScannerWithReader($dataOneColumnNoDuplicates);
        Assert::areIdentical(array(), $scanner->getDuplicates());
    }

    function testGettingDuplicatesWhenDuplicates(){
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

        $scanner = $this->createScannerWithReader($dataOneColumnWithDuplicates);

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
}