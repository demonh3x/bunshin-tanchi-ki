<?php

include_once("PurlCalculator.php");

abstract class BasePurlCalculator implements PurlCalculator{
    protected  $firstnameField, $surnameField, $salutationField;

    function __construct($firstnameField, $surnameField, $salutationField){
        $this->firstnameField = $firstnameField;
        $this->surnameField = $surnameField;
        $this->salutationField = $salutationField;
    }

    function getFirstName(&$row){
        return $row[$this->firstnameField];
    }

    function getShortFirstName(&$row){
        return substr($this->getFirstName($row), 0, 1);
    }

    function getSurname(&$row){
        return $row[$this->surnameField];
    }

    function getShortSurname(&$row){
        return substr($this->getSurname($row), 0, 1);
    }

    function getSalutation(&$row){
        return isset($row[$this->salutationField])? $row[$this->salutationField]: "";
    }

    function getSalutationHyphenatedBeginning(&$row){
        $salutation = $this->getSalutation($row);
        if (!empty($salutation)) $salutation .= "-";
        return $salutation;
    }

    function getSalutationHyphenatedEnding(&$row){
        $salutation = $this->getSalutation($row);
        if (!empty($salutation)) $salutation = "-" . $salutation;
        return $salutation;
    }
}