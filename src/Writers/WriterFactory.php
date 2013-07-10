<?php

include_once("Writer.php");

interface WriterFactory {
    /**
     * Create a new writer unique for the id.
     * @param $id
     * The identifier to this writer.
     * @return Writer
     * A writer ready to use.
     */
    function createWriter($id);
}