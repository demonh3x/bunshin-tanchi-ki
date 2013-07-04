<?php

include_once("Filter.php");

class OnlyLettersFilter implements Filter{
    function filter($text){
        return '';
    }
}