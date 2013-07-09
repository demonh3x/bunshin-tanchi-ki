<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Writers/CsvWriter.php");

class TestCsvWriter extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createWriter(){
        return Core::getCodeCoverageWrapper("CsvWriter");
    }

    private function deleteFile($path){
        if (file_exists($path)){
            unlink($path);
            if (file_exists($path)){
                throw new \Exception("Cannot delete the file: [$path]");
            }
        }
    }

    function testCreateFile(){
        $writer = $this->createWriter();

        $path = "sampleFiles/test_csv_writer.csv";
        $this->deleteFile($path);

        $writer->create($path);
        Assert::isTrue(file_exists($path));

        $writer->__destruct();
        Assert::isFalse(file_exists($path));
    }

}