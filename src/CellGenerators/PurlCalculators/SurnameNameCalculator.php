<?php

include_once("BasePurlCalculator.php");

class SurnameNameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSurname($row) . $this->getFirstName($row);
    }
}