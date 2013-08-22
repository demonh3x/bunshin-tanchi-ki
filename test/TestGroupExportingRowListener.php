<?php

namespace Enhance;

include_once(__ROOT_DIR__ . "src/RowListeners/GroupsExportingRowListener.php");
include_once(__ROOT_DIR__ . "src/Writers/RamWriter.php");
include_once(__ROOT_DIR__ . "src/RandomReaders/RamRandomReader.php");

include_once("mocks/MockRamWriterFactory.php");

class TestGroupExportingRowListener extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createListener(\WriterFactory $factory){
        return new MockGroupsExportingRowListener($factory);
    }

    public function testDoNotCreateSameWriterTwice(){
        $listener = $this->createListener(new MockWriterFactory());
        $hash = "asdf";
        $listener->getWriter_($hash);

        try {
            $listener->getWriter_($hash);
        } catch (\Exception $e){
            throw $e;
        }
    }
}

class MockGroupsExportingRowListener extends \GroupsExportingRowListener {
    public function getWriter_($id){
        return $this->getWriter($id);
    }

    function receiveRow(\RandomReader $reader, $rowIndex, $rowHash){
    }
}

include_once(__ROOT_DIR__ . "src/Writers/NullWriter.php");
class MockWriterFactory implements \WriterFactory {
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