<?php
namespace Enhance;

define("__ROOT_DIR__", "../");

include_once('EnhanceTestFramework.php');
$filterRegex =  "//";
$excludeRegex = "//";

foreach (glob("Test*.php") as $filename)
{
    if ($excludeRegex !== "//" && preg_match($excludeRegex, $filename)){
        continue;
    }
    if (preg_match($filterRegex, $filename)){
        include_once($filename);
    }
}

Core::runTests();