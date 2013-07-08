<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashDuplicatesScanner.php");

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

    function testGettingUniquesWhenNoDuplicatesAllColumns(){
        $dataOneColumnNoDuplicates = array(
            array(
                "Column1" => "Foo", "Column2" => "asdf", "Column3" => "qwer"
            ),
            array(
                "Column1" => "Bar", "Column2" => "asdf", "Column3" => "qwer"
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

        $scanner = $this->createScannerWithReader($dataOneColumnWithDuplicates);

        $uniques = array(
            array(
                "Column1" => "Foo", "Column2" => "asdf", "Column3" => "qwer"
            ),

        );
        Assert::areIdentical($uniques, $scanner->getUniques());
    }

    function testGettingUniquesWhenDuplicatesWatchingOneColumn(){
        $dataOneColumnWithDuplicates = array(
            array(
                "Column1" => "Foo", "Column2" => "asdf", "Column3" => "qwer"
            ),
            array(
                "Column1" => "Bar", "Column2" => "qwer", "Column3" => "asdf"
            ),
            array(
                "Column1" => "Asdf", "Column2" => "asdf", "Column3" => "foo"
            )
        );

        $scanner = $this->createScannerWithReader($dataOneColumnWithDuplicates);
        $scanner->watchColumns(array("Column2"));

        $uniques = array(
            array(
                "Column1" => "Bar", "Column2" => "qwer", "Column3" => "asdf"
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

        $scanner = $this->createScannerWithReader($dataOneColumnWithDuplicates);

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

    function testGettingDuplicatesWhenDuplicatesWatchingTwoColumns(){
        $dataWithDuplicates = array(
            array(
                "Column1" => "Foo", "Column2" => "asdf", "Column3" => "qwer"
            ),
            array(
                "Column1" => "Bar", "Column2" => "qwer", "Column3" => "asdf"
            ),
            array(
                "Column1" => "Bar", "Column2" => "asdf", "Column3" => "qwer"
            )
        );

        $scanner = $this->createScannerWithReader($dataWithDuplicates);
        $scanner->watchColumns(array("Column2", "Column3"));

        $duplicates = array(
            array(
                array(
                    "Column1" => "Foo", "Column2" => "asdf", "Column3" => "qwer"
                ),
                array(
                    "Column1" => "Bar", "Column2" => "asdf", "Column3" => "qwer"
                )
            )
        );
        Assert::areIdentical($duplicates, $scanner->getDuplicates());
    }

    function testGettingDuplicatesWhenDuplicatesWithFilters(){
        $dataOneColumnWithFilterDuplicates = array(
            array(
                "Column1" => "Foo"
            ),
            array(
                "Column1" => "Bar"
            ),
            array(
                "Column1" => "bar"
            )
        );

        $scanner = $this->createScannerWithReader($dataOneColumnWithFilterDuplicates);
        $scanner->setFilter(new LowercaseMockFilter());

        $duplicates = array(
            array(
                array(
                    "Column1" => "Bar"
                ),
                array(
                    "Column1" => "bar"
                )
            )
        );
        Assert::areIdentical($duplicates, $scanner->getDuplicates());
    }
}