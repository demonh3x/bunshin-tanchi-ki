<?php

include_once("Filter.php");

class TrimFilter implements Filter{
    function filter($text){
        return trim($text);
    }
}

//MIERDA DE MAC