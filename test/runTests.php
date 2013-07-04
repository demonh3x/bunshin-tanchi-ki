<?php
namespace Enhance;

define("__ROOT_DIR__", "../");

include_once('EnhanceTestFramework.php');
$filterRegex =  "//";

foreach (glob("Test*.php") as $filename)
{
    if (preg_match($filterRegex, $filename)){
        include_once($filename);
    }
}

Core::runTests();