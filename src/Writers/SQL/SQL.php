<?php

class SQL
{
    static function insert($tabla, $datos){
        $sql = "insert into $tabla(";
        $sql .= implode(",", array_keys($datos));
        $sql .= ") values ('";
        $sql .= implode("','", array_values($datos));
        $sql .= "')";

        return $sql;
    }

    private static function where($condiciones){
        $str_condiciones = "";

        if (count($condiciones) > 0){
            $array_condiciones = [];
            foreach ($condiciones as $clave => $valor){
                $array_condiciones[] = "$clave = '$valor'";
            }

            $str_condiciones = implode(" and ", $array_condiciones);
            $str_condiciones = " where $str_condiciones";
        }

        return $str_condiciones;
    }

    static function select($tabla, $columnas = null, $condiciones = null){
        $sql = "select ";

        if (empty($columnas)){
            $sql .= "*";
        } else {
            if (is_array($columnas)){
                $sql .= implode(",", $columnas);
            } else {
                $sql .= $columnas;
            }
        }

        $sql .= " from $tabla";
        $sql .= static::where($condiciones);

        return $sql;
    }

    static function delete($tabla, $condiciones){
        if ($condiciones == null){
            $sql = "truncate $tabla";
        } else {
            $sql = "delete from $tabla";
            $sql .= static::where($condiciones);
        }

        return $sql;
    }

    static function update($tabla, $datos, $condiciones){
        $sql = "update $tabla set ";

        $datos_procesados = [];
        foreach ($datos as $columna => $valor){
            $datos_procesados[] = "$columna='$valor'";
        }
        $sql .= implode(", ", $datos_procesados);

        $sql .= static::where($condiciones);

        return $sql;
    }
}
