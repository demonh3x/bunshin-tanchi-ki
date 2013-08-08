<?php

include_once("BasePurlCalculator.php");

class Salutation_Surname_NameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSalutationHyphenatedBeginning($row) . $this->getSurname($row) . "-" . $this->getFirstName($row);
    }
}