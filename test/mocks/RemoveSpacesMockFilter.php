<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/Filter.php");

class RemoveSpacesMockFilter implements \Filter{
    function applyTo($text){
        switch($text){
            case " hi":
                return "hi";
            case " h i ":
                return "hi";
            case " hello":
                return "hello";
            case " Hi":
                return "Hi";
            case " h I ":
                return "hI";
            case " B a r ":
                return "Bar";
            default:
                throw new \Exception("RemoveSpacesMockFilter's case ($text) is not defined");
        }
    }
}