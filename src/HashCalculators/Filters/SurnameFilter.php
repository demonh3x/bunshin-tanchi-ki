<?php

include_once("Filter.php");

class SurnameFilter implements Filter{

    function applyTo($text){
        return $text;
    }
}