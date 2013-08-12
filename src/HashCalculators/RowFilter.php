<?php

interface RowFilter {
    /**
     * Apply the filtering to a row.
     * @param array $row
     * The input row.
     * @return array
     * The transformed row.
     */
    function applyTo($row);
}