<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Comparators/Filters/Filter.php");

class NoSpacesMockFilter implements \Filter{
    function filter($text){
        switch($text){
            case " h i ":
                return "hi";
            case " H I ":
                return "HI";
            default:
                throw new \Exception("NoSpacesMockFilter's case ($text) is not defined");
        }
    }
}