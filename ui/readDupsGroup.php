<?php

include_once("../src/RandomReaders/CsvColumnRandomReader.php");

class Arrays {

    public  $arrayRows = array(),
            $arrayPURLs = array();

    function __construct(){
        $dupsGroupPath = $_REQUEST["dupsGroup"];
        $identifyingFile = $this->getIdentifyingValuesFile($dupsGroupPath);


        $CsvRandomReader = new CsvColumnRandomReader($dupsGroupPath);
        for ($i = 0; $i < $CsvRandomReader->getRowCount(); $i++)
        {
            array_push( $this->arrayRows, $CsvRandomReader->readRow($i) );
        }

        $identifyingFileReader = new CsvColumnRandomReader($identifyingFile);
        for ($i = 0; $i < $identifyingFileReader->getRowCount(); $i++)
        {
            $value = $identifyingFileReader->readRow($i);
            $this->arrayPURLs[reset($value)] = "";
        }
    }

    private function getIdentifyingValuesFile($dupsGroup){
        $parts = explode(__DUPLICATES_FOLDER__, $dupsGroup);
        $identifyingFile = $parts[0] . __IDENTIFYING_VALUES_FILE__;

        return $identifyingFile;
    }

    function getArrayRows(){
        return json_encode($this->arrayRows);
    }

    function getArrayPURLs(){
        return json_encode($this->arrayPURLs);
    }

}