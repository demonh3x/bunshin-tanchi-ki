<?php

interface Writer {
    function create($path);
    function isReady();
    function writeRow($data);
}