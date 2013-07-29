<?php

class LockingCsvWriter extends CsvWriter implements Writer{
    private function isLocked(){
        return file_exists($this->path . ".lock");
    }

    private function lock(){
        touch($this->path . ".lock");
    }

    private function unlock(){
        if ($this->isLocked()){
            unlink($this->path . ".lock");
        }
    }

    private $path;
    private $isThisFileLocking = false;

    function create($path){
        $this->path = $path;

        if (!$this->isLocked()){
            parent::create($path);

            $this->isThisFileLocking = true;
            $this->lock();
        }
    }

    function __destruct(){
        if ($this->isThisFileLocking){
            $this->unlock();
        }
    }
}