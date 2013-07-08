<?php

include_once("Filter.php");

class NoFilter implements Filter{
    function applyTo($text){
        return $text;
    }
}