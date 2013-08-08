<?php

include_once("BasePurlCalculator.php");

class Salutation_Surname_NCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSalutationHyphenatedBeginning($row) . $this->getSurname($row) . "-" . $this->getShortFirstName($row);
    }
}