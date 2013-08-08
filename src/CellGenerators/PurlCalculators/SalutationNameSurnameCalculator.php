<?php

include_once("BasePurlCalculator.php");

class SalutationNameSurnameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSalutation($row) . $this->getFirstName($row) . $this->getSurname($row);
    }
}