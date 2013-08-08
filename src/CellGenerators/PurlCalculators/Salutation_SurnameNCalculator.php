<?php

include_once("BasePurlCalculator.php");

class Salutation_SurnameNCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSalutationHyphenatedBeginning($row) . $this->getSurname($row) . $this->getShortFirstName($row);
    }
}