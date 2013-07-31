<?php

include_once("BasePurlCalculator.php");

class Salutation_NameSCalculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->salutationField] . "-" . $row[$this->firstnameField] . substr($row[$this->surnameField], 0, 1);
    }
}