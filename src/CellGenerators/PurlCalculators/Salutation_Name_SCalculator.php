<?php

include_once("BasePurlCalculator.php");

class Salutation_Name_SCalculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->salutationField] . "-"  . $row[$this->firstnameField] . "-" . substr($row[$this->surnameField], 0, 1);
    }
}