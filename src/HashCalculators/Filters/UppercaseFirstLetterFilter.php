<?php

include_once("Filter.php");

class UppercaseFirstLetterFilter implements Filter{
    function applyTo($text){
        return ucwords(strtolower($text));
    }
}