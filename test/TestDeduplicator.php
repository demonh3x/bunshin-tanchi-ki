<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/Deduplicator.php");

class TestDeduplicator extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    function testSetRules(){
        $deduplicator = new \Deduplicator();
        /*$deduplicator->setRule();
        $deduplicator->setFilter($column, $function);*/
        Assert::fail();
    }
}