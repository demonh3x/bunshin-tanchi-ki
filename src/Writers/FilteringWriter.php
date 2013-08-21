<?php

include_once("Writer.php");
include_once(__ROOT_DIR__ . "src/HashCalculators/RowFilter.php");
class FilteringWriter implements Writer{
    private $writer, $cleaningRowFilter;

    function __construct(Writer $writer, RowFilter $cleaning){
        $this->writer = $writer;
        $this->cleaningRowFilter = $cleaning;
    }

    function writeRow($data){
        $this->writer->writeRow(
            $this->cleaningRowFilter->applyTo($data)
        );
    }
}