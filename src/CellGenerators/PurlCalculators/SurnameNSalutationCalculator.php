<?php

include_once("BasePurlCalculator.php");

class SurnameNSalutationCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSurname($row) . $this->getShortFirstName($row) . $this->getSalutation($row);
    }
}