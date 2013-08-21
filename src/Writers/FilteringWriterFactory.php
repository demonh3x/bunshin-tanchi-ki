<?php

include_once("WriterFactory.php");
include_once(__ROOT_DIR__ . "src/HashCalculators/RowFilter.php");

include_once("FilteringWriter.php");
class FilteringWriterFactory implements WriterFactory{
    private $factory, $filter;

    function __construct(WriterFactory $factory, RowFilter $clean){
        $this->factory = $factory;
        $this->filter = $clean;
    }

    function createWriter($id){
        return new FilteringWriter(
            $this->factory->createWriter($id),
            $this->filter
        );
    }
}