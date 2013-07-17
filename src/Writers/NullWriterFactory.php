<?php

include_once("WriterFactory.php");

class NullWriterFactory implements WriterFactory{
    function createWriter($id){
        return new NullWriter();
    }
}

class NullWriter implements Writer{
    function create($path){}
    function isReady(){}
    function writeRow($data){}
}
