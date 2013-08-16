<?php

include_once("RandomReader.php");
include_once(__ROOT_DIR__ . "src/HashCalculators/RowFilter.php");
class FilteredRandomReader implements RandomReader{
    private $reader;
    private $filter;

    function __construct(RandomReader $reader, RowFilter $filter){
        $this->reader = $reader;
        $this->filter = $filter;
    }

    function readRow($index){
        return $this->filter->applyTo(
            $this->reader->readRow($index)
        );
    }

    function getRowCount(){
        return $this->reader->getRowCount();
    }
}