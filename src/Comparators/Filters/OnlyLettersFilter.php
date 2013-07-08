<?php

include_once("Filter.php");

class OnlyLettersFilter implements Filter{
    function applyTo($text){
        $letters = array("a","b","c","d","e","f","g","h","i","j","k","l",
            "m","n","ñ","o","p","q","r","s","t","u","v","w","x","y","z",
            "A","B","C","D","E","F","G","H","I","J","K","L","M","N","Ñ",
            "O","P","Q","R","S","T","U","V","W","X","Y","Z"," "
        );
        
        $onlyLettersText = "";
        for($i = 0; $i < strlen($text); $i++)
        {
            foreach($letters as $letter){
                if ($text[$i] == $letter )
                {
                    $onlyLettersText .= $text[$i];
                }
            }
        }
        return $onlyLettersText;
    }
}