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
}