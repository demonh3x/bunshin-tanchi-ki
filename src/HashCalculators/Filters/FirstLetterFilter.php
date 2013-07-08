<?php

include_once("Filter.php");

class FirstLetterFilter implements Filter{
    function applyTo($text){
        if ($text != "")
        {
            $text = substr($text, 0, 1);
        }

        return $text;
    }
}