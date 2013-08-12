<?php

include_once("HashCalculator.php");

class NullHashCalculator implements HashCalculator{
    function calculate(Array $data){
        if (is_object($data)){
            return spl_object_hash($data);
        }

        if (is_array($data)){
            $data = serialize($data);
        }
        return hash('md4',$data);
    }
}
