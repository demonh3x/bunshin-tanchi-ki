<?php

include_once("Filter.php");

class LowercaseFilter implements Filter{
    function applyTo($text){
        return strtolower($text);
    }
}