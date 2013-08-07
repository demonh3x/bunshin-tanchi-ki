<?php

include_once("BasePurlCalculator.php");

class NameSSalutationCalculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->firstnameField] . substr($row[$this->surnameField], 0, 1) . $row[$this->salutationField];
    }
}