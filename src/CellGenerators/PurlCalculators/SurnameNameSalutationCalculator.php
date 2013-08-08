<?php

include_once("BasePurlCalculator.php");

class SurnameNameSalutationCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSurname($row) . $this->getFirstName($row) . $this->getSalutation($row);
    }
}