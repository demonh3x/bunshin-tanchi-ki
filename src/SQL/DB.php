<?php
class DB
{
    public static $base1;

    private $mysqli;

    function __construct($ip, $user, $password, $schema){
        $this->mysqli = new mysqli($ip, $user, $password, $schema);
    }

    function __destruct(){
        $this->mysqli->close();
    }

    /**
     * Realiza una consulta sql a la base de datos.
     * @param $sql
     * La consulta sql
     * @return int|mixed
     * Un array de arrays asociativos que contiene los resultados o<br>
     * el número de filas afectadas si no devuelve resultados.
     * @throws Exception
     * Si ha ocurrido un error en la consulta.
     */
    function query($sql){
        $correcto = $this->mysqli->real_query($sql);

        if($correcto){
            $resultado = $this->mysqli->use_result();

            if (!empty($resultado)){
                $array_resultados = $resultado->fetch_all(MYSQLI_ASSOC);
                return $array_resultados;
            } else {
                $filas_afectadas = $this->mysqli->affected_rows;
                return $filas_afectadas;
            }
        } else {
            throw new Exception("Error en la consulta: $sql");
        }
    }

    /**
     * Obtener las tablas existentes.
     * @return array
     * Un array de strings con los nombres de las tablas.
     */
    function tables(){
        $retorno = [];

        $resultados = $this->query("show tables");

        for($i = 0; $i < count($resultados); $i++){
            $retorno[] = array_values($resultados[$i])[0];
        }

        return $retorno;
    }
}