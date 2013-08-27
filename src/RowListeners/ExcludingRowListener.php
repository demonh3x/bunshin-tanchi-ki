<?php

include_once("RowListener.php");
include_once(__ROOT_DIR__ . "src/HashCalculators/RowFilter.php");
include_once(__ROOT_DIR__ . "src/HashCalculators/NullRowFilter.php");

class ExcludingRowListener implements RowListener{
    private $listener;
    private $excludeRowsFrom;

    function __construct(RowListener $listener, $excludeRowsFrom){
        $this->listener = $listener;
        $this->excludeRowsFrom = $excludeRowsFrom;
    }

    function receiveRow(RandomReader $reader, $rowIndex){
        if (!in_array($reader, $this->excludeRowsFrom))
        {
            $this->listener->receiveRow($reader, $rowIndex);
        }
    }
}