<?php

include_once("Writer.php");
class EncryptingWriter implements Writer{
    private $writer, $key;

    function __construct(Writer $writer, $key){
        $this->writer = $writer;
        $this->key = $key;
    }

    function writeRow($data) {
        $this->writer->writeRow(
            $this->encryptData($data)
        );
    }

    private function encryptData($data){
        $return = array();

        foreach ($data as $column => $value){
            $encryptedColumn = $this->encrypt($column);
            $encryptedValue = $this->encrypt($value);
            $return[$encryptedColumn] = $encryptedValue;
        }

        return $return;
    }

    private function encrypt($text){
        $keyMD5 = md5($this->key);
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $keyMD5, $text, MCRYPT_MODE_CBC, md5($keyMD5)));
    }
}