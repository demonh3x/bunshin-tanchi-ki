<?php

namespace Enhance;

class MockRamWriterFactory implements \WriterFactory{
    public $createdWriters = array();

    function createWriter($id){
        $ramId = "testMockRamWriterFactory_$id";
        unset($GLOBALS[$ramId]);

        $writer = new \RamWriter($ramId);

        $this->createdWriters[$ramId] = &$writer;

        return $this->createdWriters[$ramId];
    }
}