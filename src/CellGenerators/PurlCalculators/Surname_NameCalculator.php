<?php

include_once("BasePurlCalculator.php");

class Surname_NameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSurname($row) . "-" . $this->getFirstName($row);
    }
}