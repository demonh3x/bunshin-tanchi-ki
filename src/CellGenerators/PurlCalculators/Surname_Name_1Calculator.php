<?php

include_once("BasePurlCalculator.php");

class Surname_Name_1Calculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSurname($row) . "-" . $this->getFirstName($row) . "-1";
    }
}