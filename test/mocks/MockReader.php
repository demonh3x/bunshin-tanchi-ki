<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Readers/Reader.php");

class MockReader implements \Reader{
    private $cursor = 0, $resource = array();

    function setResource($resource){
        $this->resource = $resource;
    }

    function open($path){
    }
    function isReady(){
        return true;
    }
    function readRow(){
        $data = $this->resource[$this->cursor];

        if(!$this->isEof()){
            $this->cursor++;
        }

        return $data;
    }
    function isEof(){
        return $this->cursor >= count($this->resource);
    }
}