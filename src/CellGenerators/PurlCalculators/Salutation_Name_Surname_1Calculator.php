<?php

include_once("BasePurlCalculator.php");

class Salutation_Name_Surname_1Calculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSalutationHyphenatedBeginning($row) . $this->getFirstName($row) . "-" . $this->getSurname($row) . "-1";
    }
}