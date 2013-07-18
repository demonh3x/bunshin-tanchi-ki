<?php
    include_once("../src/Writers/CsvWriter.php");
    include_once("common.php");


    $slicedURL = explode("/", $fullFilePath);
    $fileName = $slicedURL[count($slicedURL) - 1];
    echo $fileName;

    $stringFromJavascript = $_POST['arrayAsString'];
    $arrayFromJavascript = json_decode($stringFromJavascript);
    print_r($arrayFromJavascript);

    $writer = new CsvWriter();

    $writer->create(__VIEW_UNIQUES_FILE__);
    $writer->writeRow($arrayFromJavascript);
    $writer->__destruct();
    $fullFilePath = $_REQUEST["dupsGroup"];

    //unlink($fileName);
