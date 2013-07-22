<?php

include_once("Filter.php");

class FirstNameFilter implements Filter{
    private function stringToUppercase ($string) {

        $string = str_replace(
            array("a","b","c","d","e","f","g","h","i","j","k","l",
                "m","n","o","p","q","r","s","t","u","v","w","x","y","z"),
            array("A","B","C","D","E","F","G","H","I","J","K","L",
                "M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"),
            $string
        );

        $string = str_replace(
            array('à', 'á', 'â', 'ã', 'ä', 'å', 'æ'),
            array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ'),
            $string
        );

        $string = str_replace(
            array('é', 'è', 'ë', 'ê'),
            array('É', 'È', 'Ë', 'Ê'),
            $string
        );

        $string = str_replace(
            array('í', 'ì', 'ï', 'î'),
            array('Í', 'Ì', 'Ï', 'Î'),
            $string
        );

        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'õ', 'ø'),
            array('Ó', 'Ò', 'Ö', 'Ô', 'Õ', 'Ø'),
            $string
        );

        $string = str_replace(
            array('ú', 'ù', 'ü', 'û'),
            array('Ú', 'Ù', 'Ü', 'Û'),
            $string
        );

        $string = str_replace(
            array('ñ', 'ç', 'ð', 'ý', 'ÿ', 'þ', 'ß'),
            array('Ñ', 'Ç', 'Ð', 'Ý', 'Ÿ', 'Þ', 'ß'),
            $string
        );

        return $string;
    }

    function applyTo($text){

        header("Content-Type: text/html; charset=utf-8");

        $delimitedNameBySpaces = mb_split(" ", $text);
        $text = $delimitedNameBySpaces[0];

        $delimitedNameByHyphens = mb_split("-", $text);


        $delimitedNameByHyphens[0] = mb_strtolower($delimitedNameByHyphens[0], "utf-8");
        $firstLetterUppercase = $this->stringToUppercase(mb_substr($delimitedNameByHyphens[0], 0, 1, 'utf-8'));

        $delimitedNameByHyphens[0] = $firstLetterUppercase . mb_substr($delimitedNameByHyphens[0], 1, null, 'utf-8');
        $text = $delimitedNameByHyphens[0];

        if (count($delimitedNameByHyphens) > 1)
        {
            $firstCharacterAfterHyphen = $delimitedNameByHyphens[1][0];

            $remainingCharacters = mb_strtolower(substr($delimitedNameByHyphens[1], 1), "utf-8");
            $text = $text . "-" . $firstCharacterAfterHyphen . $remainingCharacters;
        }

        return $text;
    }
}