<?php

require_once 'functions.php';
require_once __DIR__ . '/vendor/autoload.php';
$faker = Faker\Factory::create('en_EN');

$shortopts = "";
$shortopts .= "i:";
$shortopts .= "c:";
$shortopts .= "o:";
$shortopts .= "d:";
$shortopts .= "k:";
$shortopts .= "s";
$shortopts .= "h";

$longopts = array(
	"input:",
	"config:",
	"output:",
	"delimiter:",
	"skip:",
	"strict",
	"help",
);

$options = getopt($shortopts, $longopts);

$input = $options['i'] ?? $options['input'] ?? null;
$config = $options['c'] ?? $options['config'] ?? null;
$output = $options['o'] ?? $options['output'] ?? null;

if (count($options) == 0) {
	echo "Передайте все необходимые параметры. \n";
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


if (isset($options['s']) || isset($options['strict'])) {
	$strictOpen = fopen($input, 'r');
		while ($stData = fgetcsv($strictOpen, 1000, ',')) {
			$stNum = count($stData);
		}
			fclose($strictOpen);
			if ($stNum == 4) {
				echo "Исходный файл содержит необходимое количество столбцов. \n";
			} else {
					echo "Ошибка несоответствия столбцов во входном и конфигурационном файлах. \n";
					exit();
			}
}


if ((isset($options['i']) || isset($options['input'])) && (isset($options['o']) || isset($options['output'])) && (isset($options['c']) || isset($options['config']))) {


	if(!is_readable($input)) {
		echo "Входной файл недоступен для чтения или его не существует. \n";
		exit();
	}

	if(!is_readable($config)) {
		echo "Конфигурационный файл недоступен для чтения или его не существует. \n";
	}

	if(!file_exists($config)) {
		echo "Файл конфигурации отсутсвует или указан неверно. \n";
		exit();
	}

	if(!file_exists($input)) {
		echo "Входной файл отсутсвует. \n";
		exit();
	}
	$expansionIn = substr(strrchr($input, '.'), 1);
	if($expansionIn != "csv") {
		echo "Расширение входного файла не соответсвует условиям. \n";
		exit();
	}

	$expansionConf = substr(strrchr($config, '.'), 1);
	if($expansionConf != "php") {
		echo "Расширение файла конфигурации не соответсвует условиям. \n";
		exit();
	}

	$basenameConf = basename('conf.php');
	if ($basenameConf != "conf.php") {
		echo "Неправильное имя файла конфигурации. \n";
	}

	$arrFromConf = include $config;


	$inputRead = fopen($input, "r") or die("Ошибка");
	$outWrite = fopen($output, "w") or die("Ошибка");

	if(!is_writable($output)) {
		echo "Выходной файл недоступен для записи. \n";
	}
	$row = 1;
	$encodingIn = mb_detect_encoding('input.csv');
	$encodingOut = mb_detect_encoding('output.csv');

	//for ($i = 0; $data = fgetcsv($inputRead, 1000, ","); $i++) {
	while (($data = fgetcsv($inputRead, 0, Delimiter($options))) !== false) {

		if ($row == 1) {
			if (isset($options['skip'])) {
				$row++;
				continue;

			}

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


			fputcsv($outWrite, $dataFileOutput, Delimiter($options));
		}
	}


	echo "Запись в файл успешно произведена. \n";
	echo "Входной файл имеет кодировку: $encodingIn \n";
	echo "Выходной файл имеет кодировку: $encodingOut \n";


}

fclose($inputRead);
fclose($outWrite);








