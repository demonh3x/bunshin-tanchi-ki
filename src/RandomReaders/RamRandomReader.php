<?php

include_once("RandomReader.php");

class RamRandomReader implements RandomReader{
    private $pointerToGlobal;
    private $ready = false;

    function open($path){
        $this->ready = isset($GLOBALS[$path]) && is_array($GLOBALS[$path]);

        if ($this->ready){
            $this->pointerToGlobal = &$GLOBALS[$path];
        }
    }

    function isReady(){
        return $this->ready;
    }

    function readRow($index){
        return $this->pointerToGlobal[$index];
    }

    function getRowCount(){
        return count($this->pointerToGlobal);
    }
}