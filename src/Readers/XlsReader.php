<?php

include_once './lib/PHPExcel_1.7.9_doc/Classes/PHPExcel/IOFactory.php';
include_once("Reader.php");

class XlsReader implements Reader{
    private $objPHPExcel, 
            $sheetData;

    function open($path){
        $this->objPHPExcel = PHPExcel_IOFactory::load($path);
    }

    function isReady(){
        
    }

    function readRow(){
       return $this->cycleCachedRow();
    }

    protected function cycleCachedRow(){
       $this->sheetData = $this->objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
       
       return $this->sheetData;
    }

    function isEof(){
        
    }
}