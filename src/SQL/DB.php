<?php
class DB
{
    static function getAvailable($ip, $user, $password){
        $mysqli = new mysqli($ip, $user, $password);

        $return = array();

        $results = static::querySql($mysqli, SQL::showDatabases());

        for($i = 0; $i < count($results); $i++){
            $return[] = strtolower(array_values($results[$i])[0]);
        }

        return $return;
    }

    static function create($ip, $user, $password, $schema){
        $mysqli = new mysqli($ip, $user, $password);
        static::querySql($mysqli, SQL::createDatabase($schema));

        return new DB($ip, $user, $password, $schema);
    }

    private static function querySql($mysqli, $sql){
        $correct = $mysqli->real_query($sql);

        if($correct){
            $result = $mysqli->use_result();

            if (!empty($result)){
                $array_results = $result->fetch_all(MYSQLI_ASSOC);
                return $array_results;
            } else {
                $affected_rows = $mysqli->affected_rows;
                return $affected_rows;
            }
        } else {
            throw new Exception("Query error: $sql");
        }
    }

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
        return static::querySql($this->mysqli, $sql);
    }
}