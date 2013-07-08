<?php

include_once("Filter.php");

class TrimFilter implements Filter{
    function applyTo($text){
        return trim($text);
    }
}