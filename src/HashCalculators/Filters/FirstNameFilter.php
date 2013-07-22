<?php

include_once("Filter.php");

class FirstNameFilter implements Filter{
    function applyTo($text){
        header("Content-Type: text/html; charset=utf-8");

        $delimitedNameBySpaces = explode(" ", $text);
        $text = $delimitedNameBySpaces[0];

        $delimitedNameByHyphens = explode("-", $text);
        $text = ucfirst(mb_strtolower($delimitedNameByHyphens[0], 'UTF-8'));

        if (count($delimitedNameByHyphens) > 1)
        {
            $firstCharacterAfterHyphen = $delimitedNameByHyphens[1][0];

            $remainingCharacters = mb_strtolower(substr($delimitedNameByHyphens[1], 1), 'UTF-8');
            $text = $text . "-" . $firstCharacterAfterHyphen . $remainingCharacters;
            echo ($text . "<br>");
        }

        return $text;
    }
}