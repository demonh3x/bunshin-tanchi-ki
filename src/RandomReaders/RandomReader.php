<?php

interface RandomReader {
    function open($path);
    function isReady();
    function readRow($index);
    function rowCount();
}