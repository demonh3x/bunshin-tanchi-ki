<?php

include_once("Filter.php");

class LowercaseFilter implements Filter{
    function filter($text){
        return strtolower($text);
    }
}