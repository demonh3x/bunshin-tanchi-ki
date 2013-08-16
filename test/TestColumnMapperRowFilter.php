<?php
namespace Enhance;

include_once(__ROOT_DIR__ . "src/HashCalculators/ColumnMapperRowFilter.php");
class TestColumnMapperRowFilter extends TestFixture{

    public function setUp(){
    }

    public function tearDown(){
    }

    private function createMapper($columnMapping = array()){
        return Core::getCodeCoverageWrapper("ColumnMapperRowFilter", array($columnMapping));
    }

    private function assertMapping($input, $expected, $mapping = array()){
        $mapper = $this->createMapper($mapping);
        $actual = $mapper->applyTo($input);

        Assert::areIdentical($expected, $actual);
    }

    function testEmptyMappingShouldNotChangeAThing(){
        $input = array(
            "columnName1" => "value1"
        );
        $expected = array(
            "columnName1" => "value1"
        );

        $this->assertMapping($input, $expected);
    }

    private $testOneMapping = array(
        "columnName1" => "newColumnName1"
    );

    function testChangingOneColumnName(){
        $input = array(
            "columnName1" => "value1"
        );
        $expected = array(
            "newColumnName1" => "value1"
        );

        $this->assertMapping($input, $expected, $this->testOneMapping);
    }

    function testChangingOneOfTwoColumnNames(){
        $input = array(
            "columnName1" => "value1",
            "columnName2" => "value2"
        );
        $expected = array(
            "newColumnName1" => "value1",
            "columnName2" => "value2"
        );

        $this->assertMapping($input, $expected, $this->testOneMapping);
    }

    function testChangingOneOfThreeColumnNamesWithoutReordering(){
        $input = array(
            "columnName2" => "value2",
            "columnName1" => "value1",
            "columnName3" => "value3"
        );
        $expected = array(
            "columnName2" => "value2",
            "newColumnName1" => "value1",
            "columnName3" => "value3"
        );

        $this->assertMapping($input, $expected, $this->testOneMapping);
    }

    private $testThreeMappings = array(
        "columnName1" => "newColumnName1",
        "columnName2" => "newColumnName2",
        "columnName3" => "newColumnName3",
    );
    function testChangingThreeColumnNames(){
        $input = array(
            "columnName1" => "value1",
            "columnName2" => "value2",
            "columnName3" => "value3"
        );
        $expected = array(
            "newColumnName1" => "value1",
            "newColumnName2" => "value2",
            "newColumnName3" => "value3",
        );

        $this->assertMapping($input, $expected, $this->testThreeMappings);
    }
}