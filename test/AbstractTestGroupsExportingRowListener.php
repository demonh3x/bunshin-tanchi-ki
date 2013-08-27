<?php

namespace Enhance;

include_once(__ROOT_DIR__ . "src/RowListeners/GroupsExportingRowListener.php");

include_once(__ROOT_DIR__ . "src/Writers/RamWriter.php");
include_once(__ROOT_DIR__ . "src/RandomReaders/RamRandomReader.php");

include_once("mocks/MockRamWriterFactory.php");

abstract class AbstractTestGroupsExportingRowListener extends TestFixture{

    abstract protected function createListener(\HashCalculator $hashCalculator, \WriterFactory $factory);

    protected function createRamReader($ramId, $readerData){
        unset($GLOBALS[$ramId]);

        $writer = new \RamWriter($ramId);
        foreach ($readerData as $row){
            $writer->writeRow($row);
        }

        $reader = new \RamRandomReader($ramId);
        return $reader;
    }

    protected function assertCreatedGroups($expectedGroups = 0, $readers = 0, $hashes = 0){
        $hCalc = new \StringHashCalculator();
        $factory = new MockRamWriterFactory();
        $listener = $this->createListener($hCalc, $factory);

        Assert::areIdentical(0, count($factory->createdWriters));
        $data = array();
        for ($readerIndex = 0; $readerIndex < $readers; $readerIndex++){
            for ($hashIndex = 0; $hashIndex < $hashes; $hashIndex++){
                $data[] = array("column" => "value$hashIndex");
            }
        }

        for ($readerIndex = 0; $readerIndex < $readers; $readerIndex++){
            $readerN = $this->createRamReader("assertCreatedGroups-Reader$readerIndex", $data);
            for ($hashIndex = 0; $hashIndex < $hashes; $hashIndex++){
                $n = $readerIndex + $hashIndex;

                $listener->receiveRow($readerN, $n);
            }
        }

        Assert::areIdentical($expectedGroups, count($factory->createdWriters));
    }

    public function testDoNotCreateSameWriterTwice(){
        $hCalc = new \StringHashCalculator();
        $factory = new MockCreateOnceWriterFactory();
        $listener = $this->createListener($hCalc, $factory);

        $reader = $this->createRamReader("testDoNotCreateSameWriterTwice", array(
            array("column" => "value")
        ));

        $listener->receiveRow($reader, 0);
        try {
            $listener->receiveRow($reader, 0);
        } catch (\Exception $e){
            throw $e;
        }
    }

    public function testExporting(){
        $hCalc = new \StringHashCalculator();
        $mockFactory = new MockRamWriterFactory();
        $listener = $this->createListener($hCalc, $mockFactory);

        Assert::areIdentical(0, count($mockFactory->createdWriters));

        $data = array(
            array("column1" => "value1"),
            array("column1" => "value2"),
        );
        $reader1 = $this->createRamReader("reader1", $data);

        $listener->receiveRow($reader1, 0);
        Assert::areIdentical(1, count($mockFactory->createdWriters));
        $assertingReader1 = new \RamRandomReader($mockFactory->lastCreatedRamId);
        Assert::areIdentical(1, $assertingReader1->getRowCount());
        Assert::areIdentical($data[0], $assertingReader1->readRow(0));
    }
}

include_once(__ROOT_DIR__ . "src/Writers/NullWriter.php");
class MockCreateOnceWriterFactory implements \WriterFactory {
    public $createdWriters = array();

    function createWriter($id) {
        $writer = &$this->createdWriters[$id];

        if (isset($writer)){
            throw new \Exception("Writer already created for the id: $id!");
        } else {
            $writer = true;
        }

        return new \NullWriter();
    }
}