<?php

include_once("BasePurlCalculator.php");

class Surname_NCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSurname($row) . "-" . $this->getShortFirstName($row);
    }
}