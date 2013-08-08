<?php

include_once("BasePurlCalculator.php");

class Salutation_NSurnameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSalutationHyphenatedBeginning($row) . $this->getShortFirstName($row) . $this->getSurname($row);
    }
}