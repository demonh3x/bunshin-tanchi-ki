<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>


    </head>

    <body>

        <form action="getArrayFromJavascriptToPHP.php" method=post name=test onSubmit=setValue()>
            <input id="arrayAsString" name="arrayAsString" type=hidden>
            <input type=submit>
        </form>

    </body>

    <script type="text/javascript">

        function setValue()
        {
            var array_javascript = new Array();
            array_javascript = [["A","B","C","D"],["1","2","3","4"],["I","II","III","IV"]];

            var arv = JSON.stringify(array_javascript);
            test.arrayAsString.value = arv;
        }
    </script>
</html>
