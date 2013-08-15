<?php

class SQL
{
    static function insert($table, $data){
        $sql = "insert into $table(";
        $sql .= implode(",", array_keys($data));
        $sql .= ") values ('";
        $sql .= implode("','", array_values($data));
        $sql .= "')";

        return $sql;
    }

    private static function where($conditions){
        $conditions_str = "";

        if (count($conditions) > 0){
            $conditions_array = array();
            foreach ($conditions as $key => $value){
                $conditions_array[] = "$key = '$value'";
            }

            $conditions_str = implode(" and ", $conditions_array);
            $conditions_str = " where $conditions_str";
        }

        return $conditions_str;
    }

    private static function limit($limitLength, $limitStart = null) {
        $limit_str = " limit ";

        if (is_null($limitStart)){
            $limitStart = 0;
        }

        $limit_str .= $limitStart . ", " . ($limitStart + $limitLength);

        return $limit_str;
    }

    static function select($table, $columns = null, $conditions = null,  $limitLength = null, $limitStart = null){
        $sql = "select ";

        if (empty($columns)){
            $sql .= "*";
        } else {
            if (is_array($columns)){
                $sql .= implode(",", $columns);
            } else {
                $sql .= $columns;
            }
        }

        $sql .= " from $table";
        $sql .= static::where($conditions);

        if (!is_null($limitLength)) {
            $sql .= static::limit($limitLength, $limitStart);
        }

        return $sql;
    }

    static function delete($table, $conditions){
        if ($conditions == null){
            $sql = "truncate $table";
        } else {
            $sql = "delete from $table";
            $sql .= static::where($conditions);
        }

        return $sql;
    }

    static function update($table, $data, $conditions){
        $sql = "update $table set ";

        $processed_data = array();
        foreach ($data as $column => $value){
            $processed_data[] = "$column='$value'";
        }
        $sql .= implode(", ", $processed_data);

        $sql .= static::where($conditions);

        return $sql;
    }

    static function createTable ($table, $data) {
        $sql = "create table $table (";

        $processedData = array();
        foreach ($data[0] as $column => $value)
        {
            $processedData[] = $column . " varchar(100)";
        }
        $sql .= implode(", ", $processedData);
        $sql .= ")";

        return $sql;
    }

    static function showTables(){
        return "show tables";
    }

    static function showColumns($table){
        return "show columns from " . $table;
    }

    static function addColumn($table, $column, $datatype = "varchar(100)"){
        return "alter table " . $table . " add " . $column . " " . $datatype;
    }
}
