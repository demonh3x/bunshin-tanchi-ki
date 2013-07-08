<?php

include_once("Filter.php");

class FilterGroup implements Filter{
    static $instance;

    static function create(){
        static::$instance = new self();
        $arguments = func_get_args();
        static::addAll($arguments);
        return static::$instance;
    }

    private static function addAll($array){
        foreach ($array as $value){
            if (is_array($value)){
                static::addAll($value);
            } else {
                static::$instance->addFilter($value);
            }
        }
    }

    private $filters = array();

    function applyTo($text){
        return $this->applyFilters($text);
    }

    function addFilter(Filter $filter){
        $this->filters[] = $filter;
    }

    private function applyFilters($text){
        $filteredText = $text;

        foreach ($this->filters as $filter){
            $filteredText = $filter->applyTo($filteredText);
        }

        return $filteredText;
    }
}