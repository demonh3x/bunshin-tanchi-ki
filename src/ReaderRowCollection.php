<?php

class ReaderRowCollection implements Iterator{
    private $reader, $indexes;

    function __construct(RandomReader $reader, array $indexes = array()){
        $this->reader = $reader;
        $this->indexes = $indexes;
    }

    public function current(){
        return $this->reader->readRow(current($this->indexes));
    }

    public function next(){
        next($this->indexes);
    }

    public function key(){
        return current($this->indexes);
    }

    public function valid(){
        return current($this->indexes) !== false;
    }

    public function rewind(){
        reset($this->indexes);
    }
}