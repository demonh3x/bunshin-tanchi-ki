<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Readers/Reader.php");

class NotReadyMockReader implements \Reader{
    function open($path){
    }
    function isReady(){
        return false;
    }
    function readRow(){
        return array();
    }
    function isEof(){
        return false;
    }
}