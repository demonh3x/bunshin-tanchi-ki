<?php
include_once "BD.php";
include_once "SQL.php";

class Tabla
{
    private $nombre, $base_datos;

    function __construct($base_datos, $nombre){
        $this->base_datos = $base_datos;
        $this->nombre = $nombre;
    }

    /**
     * Buscar en la tabla.
     * @param array $condiciones
     * Un array asociativo con los parámetros de búsqueda.<br>
     * La clave de cada elemento del array es el atributo o columna y
     * el valor de cada elemento del array es el valor de esa columna.
     * @return mixed
     * Un array que contiene arrays asociativos con los resultados.<br>
     * La clave de cada elemento de los arrays asociativos es el atributo o columna y
     * el valor de cada elemento de los arrays asociativos es el valor de esa columna.
     */
    function buscar($condiciones = []){
        $sql = SQL::select($this->nombre, null, $condiciones);

        $resultados = $this->base_datos->consulta($sql);

        return $resultados;
    }

    /**
     * Insertar una nueva fila en la tabla.
     * @param $datos
     * Un array asociativo con los atributos o columas y sus valores correspondientes.
     * @return int
     * El numero de filas afectadas.
     */
    function insertar($datos){
        $sql = SQL::insert($this->nombre, $datos);

        return $this->base_datos->consulta($sql);
    }

    /**
     * Eliminar filas de la tabla.
     * @param array $condiciones
     * Un array asociativo con los parámetros de búsqueda.<br>
     * La clave de cada elemento del array es el atributo o columna y
     * el valor de cada elemento del array es el valor de esa columna.
     * @return int
     * El numero de filas eliminadas.
     * @throws InvalidArgumentException
     * Si no se especifica un array en el parámetro condiciones.
     */
    function borrar($condiciones){
        if (!is_array($condiciones)){
            throw new InvalidArgumentException("El argumento condiciones debe ser un array asociativo");
        }

        $sql = SQL::delete($this->nombre, $condiciones);

        return $this->base_datos->consulta($sql);
    }
}
