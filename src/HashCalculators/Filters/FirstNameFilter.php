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

    private function UpperCount($string){
        $string = preg_match_all('/([A-ZÀÁÂÃÄÅÆÉÈËÊÍÌÏÎÓÒÖÔÕØÚÙÜÛÑÇÐÝŸÞ]{1})/',$string,$foo);
        return $string;
    }

    private function simpleName($string)
    {
        $firstPartOfTheName = $this->delimitNameBySpaces($string)[0];
        $firstLetterUppercase = $this->stringToUppercase(mb_substr($firstPartOfTheName, 0, 1, 'utf-8'));
        $string = $firstLetterUppercase . mb_strtolower(mb_substr($firstPartOfTheName, 1, mb_strlen($firstPartOfTheName), 'utf-8'), 'utf-8');
        return $string;
    }

    private function compositeNamesWithOneHyphen($string)
    {
        $string = $this->delimitNameBySpaces($string)[0];
        $delimitedNameByHyphens = $this->delimitNameByHyphens($string);
        $firstCharacterAfterHyphen = $this->stringToUppercase(mb_substr($delimitedNameByHyphens[1], 0, 1, 'utf-8'));

        $remainingCharacters = mb_strtolower(substr($delimitedNameByHyphens[1], 1), "utf-8");
        $string = $delimitedNameByHyphens[0] . "-" . $firstCharacterAfterHyphen . $remainingCharacters;

        return $string;
    }

    private function delimitNameBySpaces($text)
    {
        $delimitedNameBySpaces = mb_split(" ", $text);
        return $delimitedNameBySpaces;
    }

    private function delimitNameByHyphens($text)
    {
        $delimitedNameByHyphens = mb_split("-", $text);
        return $delimitedNameByHyphens;
    }

    function applyTo($text){

        $text = $this->delimitNameBySpaces($text)[0];
        $foundUppers = $this->UpperCount($this->delimitNameByHyphens($text)[0]);

        if ($foundUppers != 2)
        {
            $text = $this->simpleName($text);
        }


        if (count($this->delimitNameByHyphens($text)) > 1)
        {
            $text = $this->compositeNamesWithOneHyphen($text);
        }

        return $text;
    }
}