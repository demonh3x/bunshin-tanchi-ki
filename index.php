<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
//            ini_set('auto_detect_line_endings', TRUE);
//            include_once 'src/Readers/CsvReader.php';
//            
//            $testCSV = new CsvReader();
//            
//            $testCSV->open("test/sampleFiles/archivo.csv");
//            
//            var_dump ($testCSV->readRow());
//            
//            echo "<hr>";////////////////////////////////////////////////////
//            define ("__ROOT_DIR__", "./");
//            include_once 'src/Readers/XlsxReader.php';
//            
//            $testXLSX = new XlsxReader();
//            
//            $testXLSX->open('test/sampleFiles/test_data.xlsx');
//            
//            var_dump($testXLSX->readRow());
//            
//            echo "<hr>";////////////////////////////////////////////////////
            define ("__ROOT_DIR__", "./");
            include_once 'src/Readers/XlsxReader.php';
            
            $testXLSX = new XlsxReader();
            
            $testXLSX->open('test/sampleFiles/test_empaty_data.xlsx');
            
//            var_dump ($testXLSX->readRow());
            ?>
    </body>
</html>
