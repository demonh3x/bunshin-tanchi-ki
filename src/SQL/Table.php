<?php
include_once "DB.php";
include_once "SQL.php";

class Table
{
    private $nombre, $base_datos;

    function __construct($base_datos, $nombre){
        $this->base_datos = $base_datos;
        $this->nombre = $nombre;
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
    function search($conditions = []){
        $sql = SQL::select($this->nombre, null, $conditions);

        $resultados = $this->base_datos->consulta($sql);

        return $resultados;
    }

    /**
     * Insertar una nueva fila en la tabla.
     * @param $data
     * Un array asociativo con los atributos o columas y sus valores correspondientes.
     * @return int
     * El numero de filas afectadas.
     */
    function insert($data){
        $sql = SQL::insert($this->nombre, $data);

        return $this->base_datos->consulta($sql);
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
    function delete($conditions){
        if (!is_array($conditions)){
            throw new InvalidArgumentException("El argumento condiciones debe ser un array asociativo");
        }

        $sql = SQL::delete($this->nombre, $conditions);

        return $this->base_datos->consulta($sql);
    }
}
