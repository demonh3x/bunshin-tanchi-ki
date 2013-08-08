<?php

include_once("Writer.php");

include_once("WriterException.php");

class RamWriter implements Writer{
    private $pointerToGlobal;

    function __construct($path){
        if (isset($GLOBALS[$path])){
            throw new WriterException("Can't use the global variable \"$path\" because it is already defined!", 100);
        }

        $this->pointerToGlobal = &$GLOBALS[$path];
        $this->pointerToGlobal = array();
    }

    function writeRow($data){
        $this->pointerToGlobal[] = $data;
    }
}