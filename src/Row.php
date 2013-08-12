<?php

class Row {
    private $reader, $index;
    private $hash, $hashCalculator;
    private $data;

    function __construct(RandomReader $reader, $index, HashCalculator $hashCalculator){
        $this->reader = $reader;
        $this->index = $index;

        $this->setHashCalculator($hashCalculator);
    }

    private function setHashCalculator(HashCalculator $hashCalculator){
        $this->hashCalculator = $hashCalculator;
        $this->hash = $this->hashCalculator->calculate(
            $this->getData()
        );
    }

    function getData(){
        return $this->reader->readRow($this->index);
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

