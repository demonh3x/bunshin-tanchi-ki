<?php

include_once("PurlCalculator.php");

abstract class BasePurlCalculator implements PurlCalculator{
    protected  $firstnameField, $surnameField, $salutationField;

    function __construct($firstnameField, $surnameField, $salutationField){
        $this->firstnameField = $firstnameField;
        $this->surnameField = $surnameField;
        $this->salutationField = $salutationField;
    }
}