<?php

require_once __DIR__ . '/vendor/autoload.php';
$faker = Faker\Factory::create('en_EN');

$shortopts = "";
$shortopts .= "i:";
$shortopts .= "c:";
$shortopts .= "o:";
$shortopts .= "d:";
$shortopts .= "s:";
$shortopts .= "st:";
$shortopts .= "h";

$longopts = array(
	"input:",
	"config:",
	"output:",
	"delimiter:",
	"skip:",
	"strict:",
	"help",
);

$options = getopt($shortopts, $longopts);

if (count($options) < 3) {
	echo "Передайте все необходимые параметры \n";
	exit();
}

if (isset($options['h']) || isset($options['help'])) {
	echo "Опции:
-i|--input       путь до исходного файла
-c|--config      путь до файла конфигурации
-o|--output      путь до файла с результатом
-d|--delimiter   задать разделитель (по умолчанию “,”)
--skip           пропускать модификацию первой строки исходного csv
--strict         проверять, что исходный файл содержит необходимое количество описанных в конфигурационном файле столбцов. При несоответствии выдавать ошибку.
-h|--help        вывести справку \n";
}


if ((isset($options['i']) || isset($options['input'])) && (isset($options['o']) || isset($options['output'])) && (isset($options['c']) || isset($options['config']))) {

	$input = $options['i'] ?? $options['input'] ?? null;
	$config = $options['c'] ?? $options['config'] ?? null;
	$output = $options['o'] ?? $options['output'] ?? null;

	if (!file_exists($config)) {
		echo "Файл конфигурации отсутсвует или указан неверно \n";
		exit();
	}

	if (!file_exists($input)) {
		echo "Входной файл отсутсвует \n";
		exit();
	}

//	if (end(explode(".", $input)) != 'php') {
//		echo "Расширение входного файла не соответсвует условиям \n";
//		exit();
//	}

	$arrFromConf = include $config;
	$row = 1;

	$inputRead = fopen($options['i'], "r") or die("Ошибка");
	$outWrite = fopen($output, "w") or die("Ошибка");
	$encodingIn = mb_detect_encoding('input.csv');
	$encodingOut = mb_detect_encoding('output.csv');

	for ($i = 0; $data = fgetcsv($inputRead, 1000, ","); $i++) {

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
		fputcsv($outWrite, $dataFileOutput);
	}

	echo "Запись в файл успешно произведена \n";
	echo "Входной файл имеет кодировку: $encodingIn \n";
	echo "Выходной файл имеет кодировку: $encodingOut \n";

	fclose($inputRead);
	fclose($outWrite);


}










