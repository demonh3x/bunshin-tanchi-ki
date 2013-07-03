<?php
namespace Enhance;

define("__ROOT_DIR__", "../");

include_once('EnhanceTestFramework.php');

foreach (glob("Test*.php") as $filename)
{
    include_once($filename);
}

Core::runTests();