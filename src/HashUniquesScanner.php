<?php

include_once("HashCalculators/HashCalculator.php");
include_once("RandomReaders/RandomReader.php");

include_once("HashList.php");
include_once("Row.php");

class HashUniquesScanner {
    private $calculator, $reader;

    private $appearedRows;

    private $pointersToUniques = array();

    function __construct(HashCalculator $calculator){
        $this->calculator = $calculator;
        $this->appearedRows = new HashList();
        $this->reader = new NullRandomReader();
    }

    function setReader(RandomReader $reader){
        $this->reader = $reader;
    }

    function getUniques(){
        $this->processAllInputRows();
        return new RowCollection($this->reader, $this->pointersToUniques);
    }

    private function processAllInputRows(){
        for ($rowIndex = 0; $rowIndex < $this->reader->getRowCount(); $rowIndex++) {
            $this->processRow($rowIndex);
        }
    }

    private function processRow($rowIndex){
        $row = $this->readRow($rowIndex);

        if ($this->isDuplicate($row)) {
            /*$this->copyFromUniquesToDuplicates($row);*/
            $this->removeUnique($row);

            /*$this->writeDuplicate($row);*/
        } else {
            $this->addUnique($row, $rowIndex);
        }
    }

    private function readRow($rowIndex){
        $row = new Row();
        $row->setHashCalculator($this->calculator);

        $data = $this->reader->readRow($rowIndex);
        $row->setData($data);

        return $row;
    }

    private function isDuplicate(Row $row){
        return $this->appearedRows->contains($row->getHash());
    }

/*    private function copyFromUniquesToDuplicates(Row $row){
        $rowIndex = &$this->pointersToUniques[$row->getHash()];
        if (isset($rowIndex)) {
            $pointedRow = new Row();
            $pointedRow->setHashCalculator($this->hashCalculator);

            $pointedRowData = $this->readRowData($rowIndex);
            $pointedRow->setData($pointedRowData);

            $this->writeDuplicate($pointedRow);
        }
    }*/

/*    private function writeDuplicate(Row $row){
        $this->getDuplicatesWriter($row)->writeRow($row->getData());
    }*/

/*    private function getDuplicatesWriter(Row $row) {
        if (!isset($this->duplicatesWriters[$row->getHash()])) {
            $this->duplicatesWriters[$row->getHash()] = $this->duplicatesWriterFactory->createWriter($row->getHash());
        }
        return $this->duplicatesWriters[$row->getHash()];
    }*/

    private function removeUnique(Row $row){
        unset($this->pointersToUniques[$row->getHash()]);
    }

    private function addUnique(Row $row, $rowIndex) {
        $this->appearedRows->add($row->getHash());
        $this->pointersToUniques[$row->getHash()] = $rowIndex;
    }

/*    private function writeUniques(){
        foreach ($this->pointersToUniques as $rowIndex){
            $row = $this->readRowData($rowIndex);
            $this->uniqueWriter->writeRow($row);
        }
    }*/
}

class ResultsGroup implements Iterator{
    private $rowCollections;
    private $collectionIndex = 0;

    function __construct($rowCollections = array()){
        $this->rowCollections = $rowCollections;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return $this->rowCollections[$this->collectionIndex]->current();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {

        // TODO: Implement next() method.
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        // TODO: Implement key() method.
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        // TODO: Implement valid() method.
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        // TODO: Implement rewind() method.
    }
}

class RowCollection implements Iterator{
    private $reader, $indexes;

    function __construct(RandomReader $reader, $indexes){
        if (!is_array($indexes)){
            throw new Exception("The indexes are not an array!");
        }

        $this->reader = $reader;
        $this->indexes = $indexes;
    }

    public function current(){
        return $this->reader->readRow(current($this->indexes));
    }

    public function next(){
        next($this->indexes);
    }

    public function key(){
        return current($this->indexes);
    }

    public function valid(){
        return current($this->indexes) !== false;
    }

    public function rewind(){
        reset($this->indexes);
    }
}

class NullRandomReader implements RandomReader{
    function open($path){
    }

    function isReady(){
        return true;
    }

    function readRow($index){
        return array();
    }

    function getRowCount(){
        return 0;
    }
}