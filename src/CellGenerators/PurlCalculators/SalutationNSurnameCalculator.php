<?php

include_once("BasePurlCalculator.php");

class SalutationNSurnameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->salutationField] . substr($row[$this->firstnameField], 0, 1) . $row[$this->surnameField];
    }
}