<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Readers/Reader.php");
include_once(__ROOT_DIR__ . "src/RandomReaders/RandomReader.php");

class NotReadyMockReader implements \Reader, \RandomReader{
    function open($path){
    }
    function isReady(){
        return false;
    }
    function readRow($index = null){
        return array();
    }
    function isEof(){
        return false;
    }
    function getRowCount(){
        return 0;
    }
}