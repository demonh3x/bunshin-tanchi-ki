<?php

interface Reader {
    /**
     * Open a resource to read it.
     * @param $path
     * The path to the resource.
     */
    function open($path);

    /**
     * Is the reader ready to read?
     * @return bool
     * True if the resource can be read. False otherwise.
     */
    function isReady();

    /**
     * Read a row from the resource and advance to the next row.
     * Every time this method is called, returns the next row.
     * @return mixed
     * An associative array with: the column names in the array's keys, and the row values in the array's values.
     */
    function readRow();

    /**
     * Is the reader at the end of the resource?
     * @return bool
     * True if the reader is at the end, false otherwise.
     */
    function isEof();
}