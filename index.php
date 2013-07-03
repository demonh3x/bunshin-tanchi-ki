<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
            $fp = fopen ( "archivo.csv" , "r" );
            while (( $data = fgetcsv ( $fp , 1000 , "," )) !== FALSE ) { // Mientras hay líneas que leer...

                $i = 0;
                foreach($data as $row) {

                    echo "Campo $i: $row<br>n"; // Muestra todos los campos de la fila actual
                    $i++ ;

                }

                echo "<br><br>nn";

            } 
            fclose ( $fp ); 
        ?>
    </body>
</html>
