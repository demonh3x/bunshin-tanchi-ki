<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Readers/Reader.php");
include_once(__ROOT_DIR__ . "src/RandomReaders/RandomReader.php");

class MockReader implements \Reader, \RandomReader{
    private $cursor = 0, $resource = array();

    function setResource($resource){
        $this->resource = $resource;
    }

    function open($path){
    }
    function isReady(){
        return true;
    }
    function readRow($index = null){
        if ($index == null){
            $data = $this->resource[$this->cursor];

            if(!$this->isEof()){
                $this->cursor++;
            }
        } else {
            $data = $this->resource[$index];
        }

        return $data;
    }
    function isEof(){
        return $this->cursor >= $this->getRowCount();
    }
    function getRowCount(){
        return count($this->resource);
    }
}