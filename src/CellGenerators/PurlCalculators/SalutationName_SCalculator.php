<?php

include_once("BasePurlCalculator.php");

class SalutationName_SCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSalutation($row) . $this->getFirstName($row) . "-" . $this->getShortSurname($row);
    }
}