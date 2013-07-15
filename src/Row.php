<?php

include_once ("HashCalculators/NullHashCalculator.php");

class Row {
    private $data, $hash, $hashCalculator;

    function __construct(){
        $this->hashCalculator = new NullHashCalculator();
    }

    function setHashCalculator(HashCalculator $hashCalculator){
        $this->hashCalculator = $hashCalculator;
    }

    function setData($data){
        $this->data = $data;
        $this->hash = $this->hashCalculator->calculate($this->data);
    }

    function getData(){
        return $this->data;
    }

    function getHash(){
        return $this->hash;
    }
}

