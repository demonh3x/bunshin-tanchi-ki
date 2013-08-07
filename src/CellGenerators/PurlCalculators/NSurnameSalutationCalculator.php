<?php

include_once("BasePurlCalculator.php");

class NSurnameSalutationCalculator extends BasePurlCalculator{
    function calculate($row){
        return substr($row[$this->firstnameField], 0, 1) . $row[$this->surnameField] . $row[$this->salutationField];
    }
}