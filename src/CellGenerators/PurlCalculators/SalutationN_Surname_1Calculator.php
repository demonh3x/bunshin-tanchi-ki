<?php

include_once("BasePurlCalculator.php");

class SalutationN_Surname_1Calculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSalutation($row) . $this->getShortFirstName($row) . "-" . $this->getSurname($row) . "-1";
    }
}