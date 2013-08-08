<?php

include_once("BasePurlCalculator.php");

class NameS_1Calculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getFirstName($row) . $this->getShortSurname($row) . "-1";
    }
}