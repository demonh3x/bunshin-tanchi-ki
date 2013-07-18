<?php

namespace Enhance;

class MockRamWriterFactory implements \WriterFactory{
    public $createdWriters = array();

    function createWriter($id){
        $ramId = "testMockRamWriterFactory_$id";
        unset($GLOBALS[$ramId]);

        $writer = new \RamWriter();
        $writer->create($ramId);
        if (!$writer->isReady()){
            throw new \Exception("The MockRamWriterFactory couldn't create a Writer with the id: [$id]");
        }

        $this->createdWriters[$ramId] = &$writer;

        return $this->createdWriters[$ramId];
    }
}