<?php

include_once("BasePurlCalculator.php");

class SalutationSurnameNameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $row[$this->salutationField] . $row[$this->surnameField] . $row[$this->firstnameField];
    }
}