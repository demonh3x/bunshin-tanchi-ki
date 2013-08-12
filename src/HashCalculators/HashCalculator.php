<?php

interface HashCalculator {
    /**
     * Calculate a hash representing the uniqueness of a row.
     * @param array $row
     * An array containing the row from witch the hash is calculated.
     * @return mixed
     * The identifying hash.
     */
    function calculate(Array $row);
}