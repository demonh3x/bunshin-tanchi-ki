<?php
namespace Enhance;

define("__FILTERED_FLAG_INDEX__", "filteredRow");

include_once(__ROOT_DIR__ . "src/HashCalculators/RowFilter.php");
class MockRowFilter implements \RowFilter {
    static function hasBeenFiltered($row){
        return isset($row[__FILTERED_FLAG_INDEX__]);
    }

    function applyTo($row) {
        $row[__FILTERED_FLAG_INDEX__] = "";
        return $row;
    }
}