<?php

namespace Enhance;

use RandomReader;

include_once(__ROOT_DIR__ . "src/RowListeners/GroupsExportingRowListener.php");
include_once(__ROOT_DIR__ . "src/Writers/RamWriter.php");
include_once(__ROOT_DIR__ . "src/RandomReaders/RamRandomReader.php");

include_once("mocks/MockRamWriterFactory.php");

class TestGroupExportingRowListenerExtending extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createListener(\WriterFactory $factory){
        return new MockExtendingGroupsExportingRowListener($factory);
    }

    public function testDoNotCreateSameWriterTwice(){
        $listener = $this->createListener(new MockCreateOnceWriterFactory());
        $hash = "asdf";
        $listener->getWriter_($hash);

        try {
            $listener->getWriter_($hash);
        } catch (\Exception $e){
            throw $e;
        }
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

    public function testExporting(){
        $factory = new MockRamWriterFactory();
        $listener = $this->createListener($factory);

        $data = array(
            array("column1" => "value1"),
            array("column1" => "value2"),
        );
        $hash1 = "hash1";


        $listener->groupId = "group1";
        $reader1 = $this->createRamReader("reader1", $data);

        try {
            new \RamRandomReader($factory->getRamId($listener->groupId));
            throw new \Exception("The writer shouldn't exist before receiving any row");
        } catch (\RandomReaderException $e){}

        $listener->receiveRow($reader1, 0, $hash1);
        $assertingReader1 = new \RamRandomReader($factory->getRamId($listener->groupId));
        Assert::areIdentical(1, $assertingReader1->getRowCount());
        Assert::areIdentical($data[0], $assertingReader1->readRow(0));


        $listener->groupId = "group2";
        $listener->receiveRow($reader1, 0, $hash1);
        $listener->receiveRow($reader1, 1, $hash1);
        Assert::areIdentical(1, $assertingReader1->getRowCount());
        $assertingReader2 = new \RamRandomReader($factory->getRamId($listener->groupId));
        Assert::areIdentical(2, $assertingReader2->getRowCount());
        Assert::areIdentical($data[0], $assertingReader2->readRow(0));
        Assert::areIdentical($data[1], $assertingReader2->readRow(1));
    }
}

class MockExtendingGroupsExportingRowListener extends \GroupsExportingRowListener {
    public function getWriter_($id){
        return $this->getWriter($id);
    }

    public $groupId = null;
    protected function getGroupId(RandomReader $reader, $rowIndex, $rowHash){
        return $this->groupId;
    }
}

include_once(__ROOT_DIR__ . "src/Writers/NullWriter.php");
class MockCreateOnceWriterFactory implements \WriterFactory {
    private $createdWriters = array();

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