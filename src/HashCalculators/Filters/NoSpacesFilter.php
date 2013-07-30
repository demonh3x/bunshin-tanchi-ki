<?php

include_once("Filter.php");

class NoSpacesFilter implements Filter{
    function applyTo($text){
        return str_replace(" ", "", $text);
    }
}