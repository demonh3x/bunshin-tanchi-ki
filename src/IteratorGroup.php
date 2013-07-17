<?php

class IteratorGroup implements Iterator{
    private $iterators;
    private $currentIteratorIndex = 0;

    function __construct(array $iterators = array()){
        foreach ($iterators as $iterator){
            if (!$iterator instanceof Iterator){
                throw new Exception("All the iterators must implement Iterator interface.");
            }
        }

        $this->iterators = $iterators;
    }

    public function current() {
        return $this->currentIterator()->current();
    }

    private function currentIterator() {
        return $this->iterators[$this->currentIteratorIndex];
    }

    public function next() {
        $this->currentIterator()->next();
        if (!$this->currentIterator()->valid()){
            $this->nextIterator();
        }
    }

    private function nextIterator() {
        if ($this->currentIteratorIndex < $this->numberOfIterators()){
            $this->currentIteratorIndex++;
        }
    }

    private function numberOfIterators() {
        return count($this->iterators);
    }

    public function key() {
        return $this->currentIteratorIndex . $this->currentIterator()->key();
    }

    public function valid() {
        return $this->numberOfIterators() === 0?
            false :
            $this->lastIterator()->valid();
    }

    private function lastIterator() {
        return $this->iterators[$this->numberOfIterators() -1];
    }

    public function rewind() {
        foreach ($this->iterators as $iterator){
            $iterator->rewind();
        }
        $this->currentIteratorIndex = 0;
    }
}