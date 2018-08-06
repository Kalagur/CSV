<?php
if(file_exists("input.csv")) {
    $fh = fopen("output.csv", "w");
    fclose($fh);
} else {
    echo "Не найден файл для чтения";
}
