<?php

include_once("BasePurlCalculator.php");

class SalutationSurnameNameCalculator extends BasePurlCalculator{
    function calculate($row){
        return $this->getSalutation($row) . $this->getSurname($row) . $this->getFirstName($row);
    }
}