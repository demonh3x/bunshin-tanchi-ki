<?php

include_once("Filter.php");

class FirstLetterFilter implements Filter{
    function applyTo($text){
        return $text;
    }
}