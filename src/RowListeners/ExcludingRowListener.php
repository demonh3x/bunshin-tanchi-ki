<?php

include_once("RowListener.php");
include_once(__ROOT_DIR__ . "src/HashCalculators/RowFilter.php");
include_once(__ROOT_DIR__ . "src/HashCalculators/NullRowFilter.php");

class ExcludingRowListener implements RowListener{
    private $listener;
    private $excludeRowsFrom = array();

    function __construct(RowListener $listener, Array $excludeRowsFrom){
        $this->listener = $listener;
        foreach ($excludeRowsFrom as $reader){
            $this->addExcludedReader($reader);
        }
    }

    private function addExcludedReader(RandomReader $reader){
        $this->excludeRowsFrom[] = $reader;
    }

    function receiveRow(RandomReader $reader, $rowIndex){
        if (!in_array($reader, $this->excludeRowsFrom))
        {
            $this->listener->receiveRow($reader, $rowIndex);
        }
    }
}