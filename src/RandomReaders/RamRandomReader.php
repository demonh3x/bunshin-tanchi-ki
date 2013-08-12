<?php

include_once("RandomReader.php");
include_once("RandomReaderException.php");

class RamRandomReader implements RandomReader{
    private $pointerToGlobal;

    function __construct($globalVariableName){
        if (!isset($GLOBALS[$globalVariableName])){
            throw new RandomReaderException("Can't use the global variable \"$globalVariableName\" because it is not defined!", 100);
        }
        if (!is_array($GLOBALS[$globalVariableName])){
            throw new RandomReaderException("Can't use the global variable \"$globalVariableName\" because it is not an array!", 101);
        }

        $this->pointerToGlobal = &$GLOBALS[$globalVariableName];
    }

    function readRow($index){
        return $this->pointerToGlobal[$index];
    }

    function getRowCount(){
        return count($this->pointerToGlobal);
    }
}