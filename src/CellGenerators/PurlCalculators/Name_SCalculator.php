<?php

include_once("BasePurlCalculator.php");

class Name_SCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getFirstName($row) . "-" . $this->getShortSurname($row);
    }
}