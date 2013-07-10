<?php
    $stringFromJavascript = $_POST['arrayAsString'];
    $arrayFromJavascript = json_decode($stringFromJavascript);
    print_r($arrayFromJavascript);
