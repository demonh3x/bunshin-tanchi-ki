<?php

class Arrays {

    public $array_php = array(
        array(
            "Column 1" => "1",
            "Column 2" => "2",
            "Column 3" => "3",
            "Column 4" => "4",
        ),
        array(
            "Column 1" => "A",
            "Column 2" => "B",
            "Column 3" => "C",
            "Column 4" => "D",
        ),
        array(
            "Column 1" => "I",
            "Column 2" => "II",
            "Column 3" => "III",
            "Column 4" => "IV",
        )
    );


    function getArray(){
        $this->array_php = json_encode($this->array_php);
        return $this->array_php;
    }

}
