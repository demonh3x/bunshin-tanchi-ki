<?php

include_once("BasePurlCalculator.php");

class SalutationNameS_1Calculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSalutation($row) . $this->getFirstName($row) . $this->getShortSurname($row) . "-1";
    }
}