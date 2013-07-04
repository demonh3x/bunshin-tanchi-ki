<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
            ini_set('auto_detect_line_endings', TRUE);
            include_once 'src/Readers/CsvReader.php';
            
            $testCSV = new CsvReader();
            
            $testCSV->open("test/sampleFiles/archivo.csv");
            
            var_dump ($testCSV->readRow());
            
            echo "<hr>";
            
            include_once 'src/Readers/XlsReader.php';
            
            $testXLS = new XlsReader();
            
            $testCSV->open('test/sampleFiles/amaya_data_template.xls');
            var_dump ($testXLS->readRow());
        ?>
    </body>
</html>
