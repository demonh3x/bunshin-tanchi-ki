<?php
namespace Enhance;

define("__ROOT_DIR__", "../");

include_once('EnhanceTestFramework.php');

Core::discoverTests(".", true, array('sampleFiles'));
Core::runTests();