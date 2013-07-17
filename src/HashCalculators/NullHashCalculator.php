<?php

class NullHashCalculator implements HashCalculator{
    function calculate($data){
        if (is_array($data)){
            return  hash('md4',serialize($data));
        }
        return spl_object_hash($data);
    }
}
