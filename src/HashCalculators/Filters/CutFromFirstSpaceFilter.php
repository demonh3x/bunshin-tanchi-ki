<?php

include_once("Filter.php");

class CutFromFirstSpaceFilter implements Filter{
    function applyTo($text){
        $firstSpace = strpos($text, " ");
        $spaceFound = (bool) $firstSpace;
        return $spaceFound? substr($text, 0, $firstSpace) : $text;
    }
}