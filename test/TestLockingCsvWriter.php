<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Writers/LockingCsvWriter.php");

class TestLockingCsvWriter extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createWriter(){
        return new \LockingCsvWriter();
    }

    private function deleteFile($path){
        if (file_exists($path)){
            unlink($path);
            if (file_exists($path)){
                throw new \Exception("Cannot delete the file: [$path]");
            }
        }
    }

    function testUnlockedFileShouldBeReady(){
        $path = "sampleFiles/test_locking_csv_writer.csv";
        $this->deleteFile($path);

        $writer = $this->createWriter();
        $writer->create($path);

        Assert::isTrue($writer->isReady());
    }

    function testOpeningAOpenFileShouldNotBeReady(){
        $path = "sampleFiles/test_locking_csv_writer.csv";
        $this->deleteFile($path);

        $writer = $this->createWriter();
        $writer->create($path);

        $writer2 = $this->createWriter();
        $writer2->create($path);

        Assert::isFalse($writer2->isReady());
    }

    function testUnlockingWhenClosingFile(){
        $path = "sampleFiles/test_locking_csv_writer.csv";
        $this->deleteFile($path);

        $writer = $this->createWriter();
        $writer->create($path);
        $writer = null;

        $writer2 = $this->createWriter();
        $writer2->create($path);

        Assert::isTrue($writer2->isReady());
    }
}