<?php
require_once $_SERVER['DOCUMENT_ROOT']."/backend/DB.php";
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/Savings.php";
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/Currency.php";
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/SavingsType.php";
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/SavingsHistory.php";
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/History.php";

$history = "";

//Проверка Типа накопления
{
    if(!isset($_POST['savings'])) {
        echo 'savings';
        exit();
    }

    $savings = $_POST['savings'];

    //Проверка существует ли такой тип накопления
    {
        $SavingsTypeArray = new SavingsType();
        $SavingsTypeArray = $SavingsTypeArray->select();

        $status = false;

        $newSavingsName = null;
        $newSavingsID = null;

        foreach ($SavingsTypeArray as $item) {
            if ($item['id'] == $savings) {
                $newSavingsName = $item['name'];
                $newSavingsID = $item['id'];

                $status = true;
                break;
            }
        }

        if(!$status) {
            echo 'savings';
            exit();
        }
    }
}

//Проверка введенной суммы
{
    $value = 0;

    if(isset($_POST['value'])) {
        $variable = $_POST['value'];
        // Проверка на пустоту
        if (!empty($variable)) {

            // Проверка на положительное число и отсутствие ведущего нуля
            if (is_numeric($variable) && $variable > 0 && strval($variable)[0] !== '0') {
                $value = $variable;

            } else {
                echo 'value';
                exit();
            }
        }
    }
}

//Проверка уникальности id
{
    if(!isset($_POST['id'])){
        echo 'error';
        exit();
    }

    $id = $_POST['id'];

    $Savings = new Savings();
    $selectOneToId = $Savings->selectOneToId($id);

    if(empty($selectOneToId)){
        echo 'error';
        exit();
    }

    //Проверка изменилась ли сумма
}

//Собираем историю изменений
{
    $history = "";
    if($newSavingsID !== $selectOneToId['type']) {
        $history .= "Накопление ID ".$selectOneToId['id']." были изменены на тип ".$newSavingsName ." ,";
    }

    if($value != $selectOneToId['value']) {
        $history .= "Баланс изменился, остаток ".$value;
    }
}


//Изменения записи
{
    $currentDateTime = new DateTime();
    $currentDateTime = $currentDateTime->format('Y-m-d H:i:s');

    // Создание подключения к базе данных
    $pdo = DB::сonnect();

    try {
        // Начало транзакции
        $pdo->beginTransaction();

        {
            $array = [
                'id' => $id,
                'type' => $savings,
                'data_update' => $currentDateTime,
                'value' => $value
            ];

            $Savings->update($pdo, $array);
        }


        //Сохраняем историю баланса
        {
            $SavingsHistory = new SavingsHistory();

            $array = [
                'savings_id' => $id,
                'value' => $value,
                'data_update' => $currentDateTime
            ];

            $SavingsHistory->create($pdo, $array);
        }


        $pdo->commit();

        if(!empty($history)){
            $array = [
                'data_status' => date('Y-m-d H:i:s'),
                'message' => $history
            ];

            $History = new History();
            $History->create($array);
        }

        echo 'update';


        exit();
    }
    catch (PDOException $e) {
        $pdo->rollBack();

        echo "error";
        exit();
    }
}