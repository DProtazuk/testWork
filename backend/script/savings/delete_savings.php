<?php

require_once $_SERVER['DOCUMENT_ROOT']."/backend/DB.php";
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/Savings.php";
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/SavingsHistory.php";
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/History.php";


//Проверка наличия get параметра
{
    if(!isset($_GET['id'])){
        header("Location: /page/pillow/pillow.php");
        exit();
    }
}

$Savings = new Savings();

//Проверка актуальности get параметра
{
    $id = $_GET['id'];

    if(empty($Savings->selectOneToId($id))){
        header("Location: /page/pillow/pillow.php");
        exit();
    }
}


// Создание подключения к базе данных
$pdo = DB::сonnect();

try {
    // Начало транзакции
    $pdo->beginTransaction();

    $Savings->delete($pdo, $id);


    //Сохраняем историю баланса
    $SavingsHistory = new SavingsHistory();

    $SavingsHistory->delete($pdo, $id);

    header("Location: /page/pillow/pillow.php");

    $pdo->commit();

    $array = [
        'data_status' => date('Y-m-d H:i:s'),
        'message' => "Накопления ID ".$id.", были удалены"
    ];

    $History = new History();
    $History->create($array);

    exit();


} catch (PDOException $e) {
    $pdo->rollBack();

    echo "error";
    exit();
}