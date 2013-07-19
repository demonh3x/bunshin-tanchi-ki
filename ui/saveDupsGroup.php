<?php
    include_once("../src/Writers/CsvWriter.php");
    include_once("common.php");


    $uniquesFilePath = $_REQUEST["uniquesFilePath"];
    echo ($uniquesFilePath);

    $dupsGroupFile = $_REQUEST["dupsGroupFilePath"];
    echo ($dupsGroupFile);

    $stringFromJavascript = $_POST['arrayAsString'];
    $arrayFromJavascript = json_decode($stringFromJavascript, TRUE);
    print_r($arrayFromJavascript);

    $writer = new CsvWriter();

    $writer->create($uniquesFilePath);
    if ($writer->isReady()){
        echo "<h1>READY</h1>";
        foreach ($arrayFromJavascript as $key=>$row)
        {
            print_r($row);
            $writer->writeRow($row);
        }
        //unlink($dupsGroupFile);
    } else {
        throw new Exception("Could not open the uniques file! [$uniquesFilePath]");
    }


