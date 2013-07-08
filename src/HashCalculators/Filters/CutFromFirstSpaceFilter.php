<?php

include_once("Filter.php");

class CutFromFirstSpaceFilter implements Filter{
    function applyTo($text){
        $firstSpace = strpos($text, " ");
        return substr($text, 0, $firstSpace);
    }
}