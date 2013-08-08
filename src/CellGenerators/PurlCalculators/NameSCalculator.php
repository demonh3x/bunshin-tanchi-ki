<?php

include_once("BasePurlCalculator.php");

class NameSCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getFirstName($row) . $this->getShortSurname($row);
    }
}