<?php

include_once("BasePurlCalculator.php");

class Salutation_N_SurnameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSalutationHyphenatedBeginning($row) . $this->getShortFirstName($row) . "-" . $this->getSurname($row);
    }
}