<?php
class DB
{
    private $mysqli;

    function __construct($ip, $user, $password, $schema){
        $this->mysqli = new mysqli($ip, $user, $password, $schema);
    }

    function __destruct(){
        $this->mysqli->close();
    }

    /**
     * Execute a query to the database.
     * @param $sql
     * The SQL query.
     * @return int|mixed
     * An asociative array with the results or<br>
     * the affected row count if it doesn't return results.
     * @throws Exception
     * If there is something wrong in the query.
     */
    function query($sql){
        $correct = $this->mysqli->real_query($sql);

        if($correct){
            $result = $this->mysqli->use_result();

            if (!empty($result)){
                $array_results = $result->fetch_all(MYSQLI_ASSOC);
                return $array_results;
            } else {
                $affected_rows = $this->mysqli->affected_rows;
                return $affected_rows;
            }
        } else {
            throw new Exception("Query error: $sql");
        }
    }
}