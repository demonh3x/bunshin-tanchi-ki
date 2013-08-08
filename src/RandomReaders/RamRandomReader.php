<?php

include_once("RandomReader.php");
include_once("InputException.php");

class RamRandomReader implements RandomReader{
    private $pointerToGlobal;

    function __construct($id){
        if (!isset($GLOBALS[$id])){
            throw new InputException("Can't use the global variable \"$id\" because it is not defined!", 100);
        }
        if (!is_array($GLOBALS[$id])){
            throw new InputException("Can't use the global variable \"$id\" because it is not an array!", 101);
        }

        $this->pointerToGlobal = &$GLOBALS[$id];
    }

    function readRow($index){
        return $this->pointerToGlobal[$index];
    }

    function getRowCount(){
        return count($this->pointerToGlobal);
    }
}