<?php

include_once("BasePurlCalculator.php");

class SalutationName_SurnameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSalutation($row) . $this->getFirstName($row) . "-" . $this->getSurname($row);
    }
}