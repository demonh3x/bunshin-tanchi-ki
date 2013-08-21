<?php

namespace Enhance;

class MockRamWriterFactory implements \WriterFactory{
    public $createdWriters = array();

    function getRamId($id){
        return "testMockRamWriterFactory_$id";
    }

    function createWriter($id){
        $ramId = $this->getRamId($id);
        unset($GLOBALS[$ramId]);

        $writer = new \RamWriter($ramId);

        $this->createdWriters[$ramId] = &$writer;

        return $this->createdWriters[$ramId];
    }
}