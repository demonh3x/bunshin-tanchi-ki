<?php

interface RandomReader {
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