<?php

require_once $_SERVER['DOCUMENT_ROOT']."/backend/DB.php";
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/Pillow.php";
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/Currency.php";
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/History.php";

if(isset($_POST['action'])){
    switch ($_POST['action']) {
        case 'create':
            create();
            break;
        case 'selectSum':
            selectSum();
            break;
        default:
            header("Location: /page/pillow/pillow.php");
            break;
    }
}
else {
    header("Location: /page/pillow/pillow.php");
    exit();
}


function create() {
    //Проверка на пустоту поля названия Подушки
    if(!isset($_POST['pillow'])){
        header("Location: /page/currency/create.php?error=name");
        exit();
    }

    $name = $_POST['pillow'];

    $Pillow = new Pillow();

    //Проверка на наличии уже подушки
    {
        $PillowArray = $Pillow->select();

        if (!empty($PillowArray)) {
            header("Location: /page/pillow/pillow.php");
            exit();
        }
    }

    if (empty($name)){
        header("Location: /page/pillow/pillow.php?error=name");
        exit();
    }

    if ($Pillow->create($name)){

        $array = [
            'data_status' => date('Y-m-d H:i:s'),
            'message' => "Добавлена новая подушка ".$name
        ];

        $History = new History();
        $History->create($array);

        header("Location: /page/pillow/pillow.php");
    }
    else {
        header("Location: /page/pillow/pillow.php?error=save");
    }
    exit();
}

function selectSum() {
    if(!isset($_POST['selectedCurrency'])){
        echo 'false';
        exit();
    }

    $targetCurrency = $_POST['selectedCurrency'];

    $Currency = new Currency();
    $currencyArray = $Currency->selectApiCurrency();

    $Currency = new Currency();
    $CurrencyArray = $Currency->selectName($targetCurrency);


    if(empty($CurrencyArray)){
        echo 'false';
        exit();
    }

    $CurrencyId = $CurrencyArray['id'];

    $query = "SELECT `currency`.`name`, SUM(value) as total FROM savings INNER JOIN `currency` ON `savings`.`currency` = `currency`.`id` GROUP BY currency";
    $query = DB::сonnect()->query($query);
    $groupedArray = $query->fetchAll(PDO::FETCH_ASSOC);


    // Функция для перевода значения в другую валюту
    function convertCurrency($value, $fromCurrency, $toCurrency, $currencyArray) {
        $fromRate = 0;
        $toRate = 0;

        // Находим курсы обмена для исходной и целевой валюты
        foreach ($currencyArray as $currency) {
            if ($currency['name'] === $fromCurrency) {
                $fromRate = $currency['value'];
            }
            if ($currency['name'] === $toCurrency) {
                $toRate = $currency['value'];
            }

            // Если оба курса найдены, выходим из цикла
            if ($fromRate !== 0 && $toRate !== 0) {
                break;
            }
        }

        // Переводим значение в целевую валюту
        return  ($value / $fromRate) * $toRate;
    }


// Переводим значения в целевую валюту и считаем общую сумму
    $totalConvertedValue = 0;
    foreach ($groupedArray as $item) {
        $currency = $item['name'];
        $total = $item['total'];
        $convertedValue = convertCurrency($total, $currency, $targetCurrency, $currencyArray);
        $totalConvertedValue += $convertedValue;
    }

    if($totalConvertedValue){
        echo $totalConvertedValue;
    }
    else {
        echo 0;
    }
    exit();
}