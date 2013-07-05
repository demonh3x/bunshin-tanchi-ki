<?php 
include_once(__ROOT_DIR__ . "lib/PHPExcel_1.7.9_doc/Classes/PHPExcel/IOFactory.php");
include_once("Reader.php");

class XlsxReader implements Reader{
    private $objPHPExcel, 
            $sheetData,
            $nextLine = 0,
            $ready = false,
            $eof = false;

    function open($path){
        $this->objReader = new PHPExcel_Reader_Excel2007();
        $this->objPHPExcel = $this->objReader->load($path);
    }

    function isReady(){
        return $this->ready;
    }

    function readRow(){
       if ($this->eof == false)
       {
           $this->nextLine = $this->nextLine + 1;
           return $this->cycleCachedRow();
       }
       else
       {
           return "The row you want to access doesn't exist. The last row is the number ".$this->nextLine.".";
       }
    }

    protected function cycleCachedRow(){
       $this->sheetData = $this->objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
       if ($this->nextLine >= count($this->sheetData))
       {
           $this->eof = true;
       }
       
       return $this->sheetData[$this->nextLine];
    }

    function isEof(){
        return $this->eof;
    }
}