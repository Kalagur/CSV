<?php

function Delimiter($options)
{
    $delimiter = ",";
    if (isset($options['d'])) {
        $delimiter = $options['d'];
        return $delimiter;
    }
    if (isset($options['delimiter'])) {
        $delimiter = $options['delimiter'];
        return $delimiter;
    }
    return $delimiter;
}


function detectEOL($input)
{
    if (($fo = fopen($input, "r")) !== false) {
        $row = fgets($fo);
        $eol = substr($row, -2);
        if ($eol == "\r\n") {
            return "\r\n";
        } elseif (substr($eol, -1) == "\n") {
            return "\n";
        }
    }
    return false;
}

function fputcsv_eol($file, $array, $eol, $delimiter = ',')
{
    fputcsv($file, $array, $delimiter);
    if ("\n" != $eol && 0 === fseek($file, -1, SEEK_CUR)) {
        if (!fwrite($file, $eol) == false) {
            fwrite($file, $eol);
        } else {
            echo "Произошла ошибка во время записи в файл \n";
        }
    }
}

function checkParams($params)
{
    foreach (array_count_values($params) as $param) {
        if ($param > 1) {
            echo "Произошло дублирование параметров \n";
            exit(1);
        }
    }
}
