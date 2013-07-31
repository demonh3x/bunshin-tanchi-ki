<?php

include_once("BasePurlCalculator.php");

class Salutation_Surname_NameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->salutationField] . "-" . $row[$this->surnameField] . "-" . $row[$this->firstnameField];
    }
}