<?php

include_once("Row.php");

class RowCollection implements Iterator{
    private $rows;

    function __construct(array $rows = array()){
        foreach ($rows as $row){
            if (!$row instanceof Row){
                throw new Exception("All the rows must be instances from the Row class.");
            }
        }
        $this->rows = $rows;
    }

    public function current(){
        return current($this->rows)->getData();
    }

    public function next(){
        next($this->rows);
    }

    public function key(){
        return key($this->rows);
    }

    public function valid(){
        return current($this->rows) !== false;
    }

    public function rewind(){
        reset($this->rows);
    }
}