<?php

class NullHashCalculator implements HashCalculator{
    function calculate($data){
        return spl_object_hash($data);
    }
}
