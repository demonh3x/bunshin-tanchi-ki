<?php

interface RandomReader {
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
     * Read a row from the resource.
     * @param $index
     * The row's index. It can't be greater or equal than row count. The indexes start at 0.
     * @return array
     * An associative array with: the column names in the array's keys, and the row values in the array's values.
     */
    function readRow($index);

    /**
     * Get the resource's row count.
     * @return int
     * The number of rows.
     */
    function getRowCount();
}