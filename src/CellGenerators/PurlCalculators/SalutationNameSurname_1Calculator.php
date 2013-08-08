<?php

include_once("BasePurlCalculator.php");

class SalutationNameSurname_1Calculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSalutation($row) . $this->getFirstName($row) . $this->getSurname($row) . "-1";
    }
}