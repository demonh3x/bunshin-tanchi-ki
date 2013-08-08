<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Writers/LockingCsvWriter.php");

class TestLockingCsvWriter extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createWriter($path){
        return new \LockingCsvWriter($path);
    }

    private function deleteFile($path){
        if (file_exists($path)){
            unlink($path);
            if (file_exists($path)){
                throw new \Exception("Cannot delete the file: [$path]");
            }
        }
    }

    function testOpeningAnOpenFileShouldThrowAWriterException(){
        $path = "sampleFiles/test_locking_csv_writer.csv";
        $this->deleteFile($path);

        $writer = $this->createWriter($path);

        $exceptionThrown = false;
        try {
            $writer2 = $this->createWriter($path);
        } catch (\WriterException $e){
            $exceptionThrown = true;
        }

        Assert::isTrue($exceptionThrown);
    }

    function testUnlockingWhenClosingFile(){
        $path = "sampleFiles/test_locking_csv_writer.csv";
        $this->deleteFile($path);

        $writer = $this->createWriter($path);
        $writer = null;

        $writer2 = $this->createWriter($path);
    }
}