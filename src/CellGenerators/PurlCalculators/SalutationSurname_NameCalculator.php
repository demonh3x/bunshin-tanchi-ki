<?php

include_once("BasePurlCalculator.php");

class SalutationSurname_NameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->salutationField] . $row[$this->surnameField] . "-" . $row[$this->firstnameField];
    }
}