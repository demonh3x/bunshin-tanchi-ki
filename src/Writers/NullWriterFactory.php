<?php

include_once("WriterFactory.php");
include_once("NullWriter.php");

class NullWriterFactory implements WriterFactory{
    function createWriter($id){
        return new NullWriter();
    }
}

