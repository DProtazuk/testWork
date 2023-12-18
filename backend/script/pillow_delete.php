<?php

require_once $_SERVER['DOCUMENT_ROOT']."/backend/DB.php";
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/History.php";
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/Pillow.php";

//Проверка наличия подушки
$Pillow = new Pillow();
$PillowArray = $Pillow->select();
if (empty($PillowArray)) {
    header("Location: /page/pillow/pillow.php");
    exit();
}

// Выполнение запроса DELETE для очистки таблиц
$sql = "DELETE FROM `savings`";
$query = DB::сonnect()->query($sql);

$sql = "DELETE FROM `pillow`";
$query = DB::сonnect()->query($sql);

$array = [
    'data_status' => date('Y-m-d H:i:s'),
    'message' => "Подушка ".$PillowArray['name']." была удалена"
];

$History = new History();
$History->create($array);

header("Location: /page/pillow/pillow.php");