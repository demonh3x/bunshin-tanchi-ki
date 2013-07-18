<?php

include_once("../src/RandomReaders/CsvRandomReader.php");

class Arrays {

    public  $arrayRows = array(),
            $arrayPURLs = array();

    function __construct(){
        $dupsGroupPath = $_REQUEST["dupsGroup"];

        $CsvRandomReader = new CsvRandomReader();
        $CsvRandomReader->open($dupsGroupPath);
        for ($i = 0; $i < $CsvRandomReader->getRowCount(); $i++)
        {
            array_push( $this->arrayRows, $CsvRandomReader->readRow($i) );
        }

        for ($i = 0; $i < 100000; $i++)
        {
            $this->arrayPURLs["MCharlott".$i] = "0";
        }

    }

    function getArrayRows(){
        return json_encode($this->arrayRows);
    }

    function getArrayPURLs(){
        return json_encode($this->arrayPURLs);
    }

}