<?php

include_once("Filter.php");

class SurnameFilter implements Filter{
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

    private function uppercasesSpacesApostrophesAndHyphensCount($string){
        $string = preg_match_all("/([A-ZÀÁÂÃÄÅÆÉÈËÊÍÌÏÎÓÒÖÔÕØÚÙÜÛÑÇÐÝŸÞ' -]{1})/",$string);
        return $string;
    }

    private function lowercasesSpacesApostrophesAndHyphensCount($string){
        $string = preg_match_all("/([a-zàáâãäåæéèëêíìïîóòöôõøúùüûñçðýÿþ' -]{1})/",$string);
        return $string;
    }

    function applyTo($text){
        $allButTheFirstCharacter = mb_substr($text, 1, mb_strlen($text), 'utf-8');
        $countUppers = $this->uppercasesSpacesApostrophesAndHyphensCount($text);
        $countLowers = $this->lowercasesSpacesApostrophesAndHyphensCount($text);
        $firstLetterUppercase = $this->stringToUppercase(mb_substr($text, 0, 1, 'utf-8'));

        if ($countUppers == mb_strlen($text))
        {
            $text = $firstLetterUppercase . mb_strtolower($allButTheFirstCharacter, "utf-8");
        }
        if ($countLowers == mb_strlen($text))
        {
            $text = $firstLetterUppercase . $allButTheFirstCharacter;
        }
        return $text;
    }
}