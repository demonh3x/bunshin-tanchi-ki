<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>

    <body>
    </body>

    <script type="text/javascript">


        var arrayPHPToJavascript = new Array();


        <?php

        include_once ("sendArrayFromPHPToJavascript.php");
        $objectGetArray = new Arrays();

        $array_php = $objectGetArray->getArray();

        echo "\narrayPHPToJavascript = [";
        for ($i = 0, $totalArrays = count($array_php); $i < $totalArrays; $i ++)
        {
            echo "[";
            for($j = 0, $totalValues = count($array_php[$i]); $j < $totalValues; $j ++)
            {
                echo "\"".$array_php[$i][$j]."\"";
                if ($j != $totalValues-1)
                {
                    echo ", ";
                }
            }
            echo "]";
            if ($i != $totalArrays-1)
            {
                echo ", ";
            }
        }
        echo "];";
        ?>

        //Reads the Javascript resulting array
        for (var k = 0; k < arrayPHPToJavascript.length; k++)
        {
            for (var i = 0; i < arrayPHPToJavascript[k].length; i++)
            {
                document.write(arrayPHPToJavascript[k][i]);
            }
        }

    </script>
</html>
