<?php

include_once("Writer.php");

class RamWriter implements Writer{
    private $pointerToGlobal;
    private $ready = false;

    function create($path){
        $this->ready = !isset($GLOBALS[$path]);

        if ($this->ready){
            $this->pointerToGlobal = &$GLOBALS[$path];
            $this->pointerToGlobal = array();
        }
    }

    function isReady(){
        return $this->ready;
    }

    function writeRow($data){
        $this->pointerToGlobal[] = $data;
    }
}