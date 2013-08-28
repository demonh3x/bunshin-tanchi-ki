<?php

include_once("Filter.php");

class NullFilter implements Filter{
    function applyTo($text){
        return $text;
    }
}