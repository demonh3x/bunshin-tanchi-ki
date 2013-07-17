<?php

class NullHashCalculator implements HashCalculator{
    function calculate($data){
        if (is_object($data)){
            return spl_object_hash($data);
        }

        if (is_array($data)){
            $data = serialize($data);
        }
        return hash('md4',$data);
    }
}
