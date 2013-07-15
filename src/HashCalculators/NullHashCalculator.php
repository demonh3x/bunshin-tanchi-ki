<?php

class NullHashCalculator implements HashCalculator{
    function calculate($data){
        throw new Exception("No hash calculator has been set!");
    }
}
