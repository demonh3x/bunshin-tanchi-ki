<?php

interface Reader {
    function open($path);
    function isReady();
    function readRow();
    function isEof();
}