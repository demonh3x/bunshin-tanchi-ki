<?php

include_once("BasePurlCalculator.php");

class Salutation_N_Surname_1Calculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSalutationHyphenatedBeginning($row) . $this->getShortFirstName($row) . "-" . $this->getSurname($row) . "-1";
    }
}