<?php

class HTML
{
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
}
