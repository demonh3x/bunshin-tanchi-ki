<?php

include_once ("HashCalculators/NullHashCalculator.php");

class Row {
    private $reader, $index;
    private $data, $hash, $hashCalculator;

    function __construct(RandomReader $reader, $index){
        $this->reader = $reader;
        $this->index = $index;
        $this->data = $reader->readRow($index);

        $this->hashCalculator = new NullHashCalculator();
    }

    function setHashCalculator(HashCalculator $hashCalculator){
        $this->hashCalculator = $hashCalculator;
        $this->hash = $this->hashCalculator->calculate($this->data);
    }

    function getData(){
        return $this->data;
    }

    function getHash(){
        return $this->hash;
    }

    function getReader(){
        return $this->reader;
    }

    function getIndex(){
        return $this->index;
    }
}

