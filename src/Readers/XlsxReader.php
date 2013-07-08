<?php


include_once(__ROOT_DIR__ . "lib/PHPExcel_1.7.9_doc/Classes/PHPExcel/IOFactory.php");
include_once("Reader.php");

class XlsxReader implements Reader{
    private $objPHPExcel = null, 
            $sheetData = null,
            $nextLine = 0,
            $ready = false,
            $eof = false;

    function open($path){
        try
        {
            $this->objPHPExcel = PHPExcel_IOFactory::load($path);
            $this->ready = true;
            $this->sheetData = $this->objPHPExcel->getActiveSheet()->toArray(null,true,true,true);            
        }
        catch (PHPExcel_Exception $e)
        {
            $this->ready = false;
            
            // Check if the file is empty
            if ($this->objPHPExcel == NULL)
            {
                $this->eof = true;
            }
        }
    }

    function isReady(){
        return $this->ready;
    }

    function readRow(){
        if ($this->eof == false)
        {
            $this->nextLine = $this->nextLine + 1;
            return $this->cycleCachedRow()[$this->nextLine];
        }
        else
        {
            return "The row you want to access doesn't exist. The last row is the number ".$this->nextLine.".";
        }
    }

    protected function cycleCachedRow(){
        if ($this->nextLine >= count($this->sheetData))
        {
            $this->eof = true;
        }


        return $this->sheetData;
    }

    function isEof(){
        return $this->eof;
    }
}