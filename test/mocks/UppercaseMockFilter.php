<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Comparators/Filters/Filter.php");

class UppercaseMockFilter implements \Filter{
    function applyTo($text){
        switch($text){
            case "hi":
                return "HI";
            case " h i ":
                return " H I ";
            default:
                throw new \Exception("UppercaseMockFilter's case ($text) is not defined");
        }
    }
}