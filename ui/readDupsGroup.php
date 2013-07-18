<?php

class Arrays {

    //$_REQUEST["dir"];

    public $arrayRows = array(),
           $arrayPURLs = array();


    function __construct(){
        for ($i = 0; $i < 5; $i++){
            array_push( $this->arrayRows,
                        array(
                            "Name" => "Maria",
                            "Surname" => "Torremolinos",
                            "Telf" => "56454565",
                            "PURL" => "MariaT",
                        ),
                        array(
                            "Name" => "Mateu",
                            "Surname" => "Charlott",
                            "Telf" => "85552555",
                            "PURL" => "MCharlott0",
                        ),
                        array(
                            "Name" => "Mary",
                            "Surname" => "Charlott",
                            "Telf" => "69156565",
                            "PURL" => "MCharlott",
                        )
            );
        }

        for ($i = 0; $i < 100000; $i++)
        {
            $this->arrayPURLs["MCharlott".$i] = "0";
        }

    }

    function getArrayRows(){
        return json_encode($this->arrayRows);
    }

    function getArrayPURLs(){
        return json_encode($this->arrayPURLs);
    }

}