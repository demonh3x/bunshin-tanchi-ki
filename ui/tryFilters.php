<?php
    include_once("common.php");

    $filterNames = getPostVar("filters");
    $text = getPostVar("text");

    $filterGroup = getFilterGroup($filterNames);

    echo $filterGroup->applyTo($text);