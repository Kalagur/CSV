<?php

require_once __DIR__. '/vendor/autoload.php';

$shortopts  = "i";
$shortopts .= "c:";
$shortopts .= "o::";
$shortopts .= "d";
$shortopts .= "skip";
$shortopts .= "strict";
$shortopts .= "h";

$longopts  = array(
    "input",
    "confige",
    "output",
    "delimiter",
    "skip",
    "strict",
    "help",
);

$options = getopt($shortopts, $longopts);
if(isset($options['h']) || isset($options['help'])) {
   echo "-i|--input file - путь до исходного файла
         -c|--config file - путь до файла конфигурации
         -o|--output file - путь до файла с результатом
         -d|--delimiter delim - задать разделитель (по умолчанию “,”)
         --skip-first - пропускать модификацию первой строки исходного csv
         --strict - проверять, что исходный файл содержит необходимое количество описанных в конфигурационном файле столбцов. При несоответствии выдавать ошибку.
         -h|--help - вывести справку";
    }




//if(file_exists("input.csv")) {
//    $readCSV = fopen("input.csv", "r");
//    echo $readCSV;
//    fclose($readCSV);
//
//
//
//    $fh = fopen("output.csv", "w");
//    fclose($fh);
//} else {
//    echo "Не найден файл для чтения \n";
//}
