<?php

interface Comparator {
    /**
     * Are the two elements equivalent?
     * @param $a
     * The first element.
     * @param $b
     * The second element.
     * @return bool
     * True if $a and $b are equivalent, false otherwise.
     */
    public function areEqual($a, $b);
}