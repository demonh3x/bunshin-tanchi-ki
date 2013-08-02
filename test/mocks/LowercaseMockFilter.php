<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/Filter.php");

class LowercaseMockFilter implements \Filter{
    function applyTo($text){
        switch($text){
            case "Hi":
                return "hi";
            case "hI":
                return "hi";
            case "Foo":
            case "FOO":
                return "foo";
            case "Bar":
            case "bar":
                return "bar";
            default:
                throw new \Exception("LowercaseMockFilter's case ($text) is not defined");
        }
    }
}