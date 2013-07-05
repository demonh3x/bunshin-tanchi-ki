<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Comparators/Filters/Filter.php");

class LowercaseMockFilter implements \Filter{
    function filter($text){
        switch($text){
            case "Hi":
                return "hi";
            case "hI":
                return "hi";
            default:
                throw new \Exception("LowercaseMockFilter's case ($text) is not defined");
        }
    }
}