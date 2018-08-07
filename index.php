<?php

require_once __DIR__. '/vendor/autoload.php';
$faker = Faker\Factory::create('en_EN');

$shortopts = "";
$shortopts .= "i:";
$shortopts .= "c:";
$shortopts .= "o:";
$shortopts .= "d:";
$shortopts .= "s:";
$shortopts .= "st:";
$shortopts .= "h";

$longopts  = array(
    "input",
    "config",
    "output",
    "delimiter",
    "skip",
    "strict",
    "help",
);

$options = getopt($shortopts, $longopts);
if(isset($options['h']) || isset($options['help'])) {
    echo "Опции:
-i|--input file          путь до исходного файла
-c|--config file         путь до файла конфигурации
-o|--output file         путь до файла с результатом
-d|--delimiter delim     задать разделитель (по умолчанию “,”)
--skip-first             пропускать модификацию первой строки исходного csv
--strict                 проверять, что исходный файл содержит необходимое количество описанных в конфигурационном файле столбцов. При несоответствии выдавать ошибку.
-h|--help                вывести справку \n";

}


$arrFromConf = include "conf.php";


if(isset($options['i']) || isset($options['c'])) {
    $row = 1;
    $input = fopen("input.csv", "r") or die("Ошибка");
    $out = fopen("output.csv", "w") or die("Ошибка");
    for ($i = 0; $data = fgetcsv($input, 1000, ","); $i++) {
        $row++;
        foreach ($data as $k => $v) {
            if (!array_key_exists($k, $arrFromConf)) {
                $dataFileOutput[$k] = $v;
            } else {
                $newconf = $arrFromConf[$k];
                if (is_null($newconf)) {
                    $dataFileOutput[$k] = "";
                } elseif (strtolower(gettype($newconf)) == "string") {
                    try {
                        $dataFileOutput[$k] = $faker->$newconf;
                    } catch (Exception $e) {
                        echo 'Выброшено исключение: ', $e->getMessage(), "\n";
                    }
                } elseif (gettype($newconf) == "object") {
                    try {
                        $dataFileOutput[$k] = $newconf($v, $data, $row, $faker);
                    } catch (Exception $e) {
                        echo 'Выброшено исключение: ', $e->getMessage(), "\n";
                    }
                } else {
                    try {
                        $dataFileOutput[$k] = $v;
                    } catch (Exception $e) {
                        echo 'Выброшено исключение: ', $e->getMessage(), "\n";
                    }
                }
            }
        }

        fputcsv($out, $dataFileOutput);
    }
    echo "Запись в файл успешно произведена \n";


    fclose($input);
    fclose($out);
}


