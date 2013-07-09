<?php

include_once("Filter.php");

class OnlyLettersFilter implements Filter{
    private static $letters = array("a","b","c","d","e","f","g","h","i","j","k","l",
        "m","n","ñ","o","p","q","r","s","t","u","v","w","x","y","z",
        "A","B","C","D","E","F","G","H","I","J","K","L","M","N","Ñ",
        "O","P","Q","R","S","T","U","V","W","X","Y","Z"," "
    );

    function applyTo($text){
        $onlyLettersText = "";
        for($i = 0; $i < strlen($text); $i++)
        {
            foreach(static::$letters as $letter){
                if ($text[$i] == $letter )
                {
                    $onlyLettersText .= $text[$i];
                }
            }
        }
        return $onlyLettersText;
    }
}