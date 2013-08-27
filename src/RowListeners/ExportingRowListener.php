<?php

include_once("RowListener.php");
include_once(__ROOT_DIR__ . "src/Writers/Writer.php");
include_once(__ROOT_DIR__ . "src/HashCalculators/RowFilter.php");
include_once(__ROOT_DIR__ . "src/HashCalculators/NullRowFilter.php");

class ExportingRowListener implements RowListener{
    private $writer;

    function __construct(Writer $writer){
        $this->writer = $writer;
    }

    function receiveRow(RandomReader $reader, $rowIndex){
        $this->writer->writeRow(
            $reader->readRow($rowIndex)
        );
    }
}