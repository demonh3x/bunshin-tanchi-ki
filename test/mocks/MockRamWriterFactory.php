<?php

namespace Enhance;

class MockRamWriterFactory implements \WriterFactory{
    public $createdWriters = array();
    public $lastCreatedRamId;

    function getRamId($id){
        return "testMockRamWriterFactory_$id";
    }

    function createWriter($id){
        $ramId = $this->getRamId($id);
        unset($GLOBALS[$ramId]);

        $writer = new \RamWriter($ramId);

        $this->createdWriters[$ramId] = &$writer;
        $this->lastCreatedRamId = $ramId;

        return $this->createdWriters[$ramId];
    }
}