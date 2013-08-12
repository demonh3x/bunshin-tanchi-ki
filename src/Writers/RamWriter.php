<?php

include_once("Writer.php");

include_once("WriterException.php");

class RamWriter implements Writer{
    private $pointerToGlobal;

    function __construct($globalVariableName){
        if (isset($GLOBALS[$globalVariableName])){
            throw new WriterException("Can't use the global variable \"$globalVariableName\" because it is already defined!", 100);
        }

        $this->pointerToGlobal = &$GLOBALS[$globalVariableName];
        $this->pointerToGlobal = array();
    }

    function writeRow($data){
        $this->pointerToGlobal[] = $data;
    }
}