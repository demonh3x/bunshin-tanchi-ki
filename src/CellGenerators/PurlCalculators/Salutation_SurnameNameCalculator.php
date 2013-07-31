<?php

include_once("BasePurlCalculator.php");

class Salutation_SurnameNameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->salutationField] . "-" . $row[$this->surnameField] . $row[$this->firstnameField];
    }
}