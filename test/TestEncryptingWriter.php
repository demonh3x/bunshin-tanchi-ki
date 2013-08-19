<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Writers/EncryptingWriter.php");

include_once(__ROOT_DIR__ . "src/Writers/RamWriter.php");
include_once(__ROOT_DIR__ . "src/RandomReaders/RamRandomReader.php");
class TestEncryptingWriter extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createWriter(\Writer $writer, $key){
        return Core::getCodeCoverageWrapper("EncryptingWriter", array($writer, $key));
    }

    function testEncryptingData(){
        $ramId = "testEncryptingWriter";

        $ramWriter = new \RamWriter($ramId);
        $key = "keyToEncrypt";
        $encWriter = $this->createWriter($ramWriter, $key);

        $encWriter->writeRow(array(
            "0" => "encrypted data"
        ));

        $ramReader = new \RamRandomReader($ramId);
        $actual = $ramReader->readRow(0);

        $expected = array(
            "bYKwvkNqlJTWeDxctdNbmZUw6YIf/SRLPZ3e5Eq8fn0=" => "3WAx162oKsIxmRo/wWdLDzNOtWzIgmQUBYJEjRz4Jp0="
        );
        Assert::areIdentical($expected, $actual);
    }
}