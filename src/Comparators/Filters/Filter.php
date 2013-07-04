<?php

interface Filter {
    /**
     * Transform a text in a way that helps comparing it later.
     * @param $text
     * The input text value.
     * @return mixed
     * The transformed result.
     */
    public function filter($text);
}