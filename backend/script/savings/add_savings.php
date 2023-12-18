<?php
require_once $_SERVER['DOCUMENT_ROOT']."/backend/DB.php";
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/Savings.php";
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/Pillow.php";
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/Currency.php";
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/SavingsType.php";
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/SavingsHistory.php";
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/History.php";


//проверка существует ли подушка
{
    $Pillow = new Pillow();
    $Pillow = $Pillow->select();

    if(empty($Pillow)){
        echo 'error';
        exit();
    }

    $idPillow = $Pillow['id'];
}

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
        $SavingsTypeName = false;

        foreach ($SavingsTypeArray as $item) {
            if ($item['id'] == $savings) {
                $SavingsTypeName = $item['name'];
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

//Проверка выбранной валюты
{
    if(!isset($_POST['currency'])) {
        echo 'currency';
        exit();
    }

    $currency = $_POST['currency'];

    //Проверка существует ли такая валюта в бд
    {
        $CurrencyArray = new Currency();
        $CurrencyArray = $CurrencyArray->selectID($currency);


        if(empty($CurrencyArray)) {
            echo 'currency';
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

//Сохраняем добавление денег
{
    $currentDateTime = new DateTime();
    $currentDateTime = $currentDateTime->format('Y-m-d H:i:s');

    $array = [
        'pillow' => $idPillow,
        'type' => $savings,
        'currency' => $currency,
        'data_create' => $currentDateTime,
        'data_update' => null,
        'value' => $value
    ];
    // Создание подключения к базе данных
    $pdo = DB::сonnect();

    try {
        // Начало транзакции
        $pdo->beginTransaction();

        $Savings = new Savings();
        $id = $Savings->create($pdo, $array);

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

        $array = [
            'data_status' => date('Y-m-d H:i:s'),
            'message' => "В подушку '".$Pillow['name']."' добавлен тип денег ID ".$id.",".$SavingsTypeName." в валюте ".$CurrencyArray['name']." с суммой ".$value
        ];

        $History = new History();
        $History->create($array);
        echo 'save';

        exit();


    } catch (PDOException $e) {
        $pdo->rollBack();

        echo "error";
        exit();
    }
}