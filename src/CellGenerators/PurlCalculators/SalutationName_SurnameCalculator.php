<?php

include_once("BasePurlCalculator.php");

class SalutationName_SurnameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->salutationField] . $row[$this->firstnameField] . "-" . $row[$this->surnameField];
    }
}