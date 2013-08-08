<?php

include_once("BasePurlCalculator.php");

class NSurnameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getShortFirstName($row) . $this->getSurname($row);
    }
}