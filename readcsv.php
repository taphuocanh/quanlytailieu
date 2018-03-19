<?php
// $file = fopen("data.csv","r");

// var_dump(file_get_contents("data.csv"));
// print_r(fgetcsv($file, 0, "|", "\n"));
// fclose($file);

$data = file_get_contents("data.csv");
echo "<pre>";
$array = explode("\n", $data);
foreach ($array as $key => $row) {
    $array[$key]= explode("|", $row);
    if ($key >0 ) {
        $array[$key] = array_combine($array[0], $array[$key]);
    }
    if (!$array[$key]) {
        unset($array[$key]);
    }
}
var_dump($array);

?>