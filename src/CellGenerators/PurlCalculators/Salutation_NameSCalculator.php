<?php

include_once("BasePurlCalculator.php");

class Salutation_NameSCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSalutationHyphenatedBeginning($row) . $this->getFirstName($row) . $this->getShortSurname($row);
    }
}