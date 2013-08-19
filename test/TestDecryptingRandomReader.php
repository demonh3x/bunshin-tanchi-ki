<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/RandomReaders/DecryptingRandomReader.php");
include_once(__ROOT_DIR__ . "src/Writers/EncryptingWriter.php");

include_once(__ROOT_DIR__ . "src/Writers/RamWriter.php");
include_once(__ROOT_DIR__ . "src/RandomReaders/RamRandomReader.php");
class TestDecryptingRandomReader extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createReader(\RandomReader $reader, $key){
        return Core::getCodeCoverageWrapper("DecryptingRandomReader", array($reader, $key));
    }

    function testDecryptingData(){
        $ramId = "testDecryptingReader";
        $key = "keyToEncrypt";
        $input = array(
            "0" => "encrypted data"
        );

        $encWriter = new \EncryptingWriter(
            new \RamWriter($ramId),
            $key
        );

        $encWriter->writeRow($input);

        $decReader = $this->createReader(
            new \RamRandomReader($ramId),
            $key
        );

        $actual = $decReader->readRow(0);
        Assert::areIdentical($input, $actual);
    }

    function testPassingGetRowCountCalls(){
        $ramId = "testPassingGetRowCountCalls";

        $writer = new \RamWriter($ramId);

        $reader = new \RamRandomReader($ramId);
        $decryptingReader = $this->createReader($reader, "");

        Assert::areIdentical($reader->getRowCount(), $decryptingReader->getRowCount());

        for ($i = 0; $i < 3 ; $i++){
            $writer->writeRow(array("0"=>"Foo"));
            Assert::areIdentical($reader->getRowCount(), $decryptingReader->getRowCount());
        }
    }
}