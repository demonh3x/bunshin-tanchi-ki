<?php

class HTML {
    static function ul($list){
        return static::undefinedList("ul", $list);
    }

    static function ol($list){
        return static::undefinedList("ol", $list);
    }

    static function undefinedList($type, $list){
        $html = "<$type>";
        foreach ($list as $li){
            $html .= "<li>$li</li>";
        }
        $html .= "</$type>";

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

    static function select($array, $class = null){
        if (count($array) == 0) {
            return "";
        }

        $class = is_null($class)? "": $class;

        $html = "<select class='$class'>";

        foreach ($array as $value){
            $html .= "<option value='$value'>$value</option>";
        }

        $html .= "</select>";

        return $html;
    }
}