<?php

class HashCalculator {
    function calculate($row){
        $hash = "";

        foreach ($row as $key => $value){
            $hash .= "$key$value";
        }

        return $hash;
    }
}