<?php

include_once("CsvWriter.php");

class LockingCsvWriter extends CsvWriter implements Writer{
    private function isLocked(){
        return file_exists($this->path . ".lock");
    }

    private function lock(){
        touch($this->path . ".lock");
    }

    private function unlock(){
        if ($this->isThisFileLocking && $this->isLocked()){
            unlink($this->path . ".lock");
        }
    }

    private $path;
    private $isThisFileLocking = false;

    function __construct($path){
        $this->path = $path;

        if ($this->isLocked()){
            throw new WriterException("The file \"$path\" is locked!", 2000);
        }

        parent::__construct($path);

        $this->isThisFileLocking = true;
        $this->lock();
    }

    function __destruct(){
        if ($this->isThisFileLocking){
            $this->unlock();
        }
    }
}