<?php
include_once "DB.php";
include_once "SQL.php";

class Table
{
    /**
     * Get the available tables.
     * @param DB $database
     * The database to look in.
     * @return array
     * An array with the table names.
     */
    static function getAvailable(DB $database){
        $return = array();

        $results = $database->query(SQL::showTables());

        for($i = 0; $i < count($results); $i++){
            $res = array_values($results[$i]);
            $return[] = $res[0];
        }

        return $return;
    }

    static function create(DB $database, $name, $columns){
        $database->query(SQL::createTable($name, $columns));
        return new Table($database, $name);
    }

    private $name, $database;

    function __construct(DB $database, $name){
        $this->database = $database;
        $this->name = $name;
    }

    /**
     * Buscar en la tabla.
     * @param array $conditions
     * Un array asociativo con los parámetros de búsqueda.<br>
     * La clave de cada elemento del array es el atributo o columna y
     * el valor de cada elemento del array es el valor de esa columna.
     * @return mixed
     * Un array que contiene arrays asociativos con los resultados.<br>
     * La clave de cada elemento de los arrays asociativos es el atributo o columna y
     * el valor de cada elemento de los arrays asociativos es el valor de esa columna.
     */
    function search($conditions = array()){
        $sql = SQL::select($this->name, null, $conditions);

        $results = $this->database->query($sql);

        return $results;
    }

    /**
     * Insertar una nueva fila en la tabla.
     * @param $data
     * Un array asociativo con los atributos o columas y sus valores correspondientes.
     * @return int
     * El numero de filas afectadas.
     */
    function insert($data){
        $sql = SQL::insert($this->name, $data);

        return $this->database->query($sql);
    }

    /**
     * Eliminar filas de la tabla.
     * @param array $conditions
     * Un array asociativo con los parámetros de búsqueda.<br>
     * La clave de cada elemento del array es el atributo o columna y
     * el valor de cada elemento del array es el valor de esa columna.
     * @return int
     * El numero de filas eliminadas.
     * @throws InvalidArgumentException
     * Si no se especifica un array en el parámetro condiciones.
     */
    function delete($conditions = null){
        if (!is_null($conditions) && !is_array($conditions)){
            throw new InvalidArgumentException("El argumento condiciones debe ser un array asociativo");
        }

        $sql = SQL::delete($this->name, $conditions);

        return $this->database->query($sql);
    }

    function drop(){
        $sql = SQL::dropTable($this->name);

        return $this->database->query($sql);
    }

    function getColumns(){
        $return = array();

        $results = $this->database->query(SQL::showColumns($this->name));

        for($i = 0; $i < count($results); $i++){
            $res = array_values($results[$i]);
            $return[] = $res[0];
        }

        return $return;
    }

    function addColumn($columnName, $dataType){
        $sql = SQL::addColumn($this->name, $columnName, $dataType);

        return $this->database->query($sql);
    }
}
