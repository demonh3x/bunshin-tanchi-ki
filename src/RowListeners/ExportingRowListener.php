<?php

include_once("RowListener.php");
include_once(__ROOT_DIR__ . "src/Writers/Writer.php");
include_once(__ROOT_DIR__ . "src/HashCalculators/RowFilter.php");
include_once(__ROOT_DIR__ . "src/HashCalculators/NullRowFilter.php");

class ExportingRowListener implements RowListener{
    private $writer, $cleaningRowFilter;

    function __construct(Writer $writer, RowFilter $cleaning = null){
        $this->cleaningRowFilter = is_null($cleaning)? new NullRowFilter(): $cleaning;
        $this->writer = $writer;
    }

    function receiveRow(RandomReader $reader, $rowIndex, $rowHash){
        $data = $reader->readRow($rowIndex);

        $this->writer->writeRow(
            $this->cleaningRowFilter->applyTo($data)
        );
    }
}