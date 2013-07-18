<?php

class HTML {
    static function ul($list){
        $html = "<ul>";
        foreach ($list as $li){
            $html .= "<li>$li</li>";
        }
        $html .= "</ul>";

        return $html;
    }

    static function a($text, $url){
        $html = "<a href='$url'>$text</a>";

        return $html;
    }

    static function table($array){
        if (count($array) == 0) {
            return "";
        }

        $html = "<table>";

        $html .= "<tr>";
        foreach ($array[0] as $key => $value){
            $html .= "<td>";
            $html .= $key;
        }

        for ($i = 0; $i < count($array); $i++){
            $html .= "<tr>";
            foreach ($array[$i] as $key => $value){
                $html .= "<td>";
                $html .= $value;
            }
        }

        $html .= "</table>";

        return $html;
    }

    static function select($array){
        if (count($array) == 0) {
            return "";
        }

        $html = "<select>";

        foreach ($array as $value){
            $html .= "<option value='$value'>$value</option>";
        }

        $html .= "</select>";

        return $html;
    }
}