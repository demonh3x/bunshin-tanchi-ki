<?php

include_once("Filter.php");

class NoFilter implements Filter{
    function filter($text){
        return '';
    }
}