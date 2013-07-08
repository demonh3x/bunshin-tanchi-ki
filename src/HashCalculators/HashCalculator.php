<?php

interface HashCalculator {
    /**
     * @param $row
     * @return string
     */
    function calculate($row);
}