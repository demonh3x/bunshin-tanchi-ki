<?php

include_once("RandomReader.php");
class DecryptingRandomReader implements RandomReader{
    private $reader, $key;

    function __construct(RandomReader $reader, $key){
        $this->reader = $reader;
        $this->key = $key;
    }

    function readRow($index){
        $encryptedData = $this->reader->readRow($index);

        $return = array();
        foreach ($encryptedData as $column => $value){
            $decryptedColumn = $this->decrypt($column);
            $decryptedValue = $this->decrypt($value);

            $return[$decryptedColumn] = $decryptedValue;
        }

        return $return;
    }

    private function decrypt($text){
        $keyMD5 = md5($this->key);
        return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $keyMD5, base64_decode($text), MCRYPT_MODE_CBC, md5($keyMD5)), "\0");
    }

    function getRowCount(){
        return $this->reader->getRowCount();
    }
}