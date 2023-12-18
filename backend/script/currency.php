<?php

require_once $_SERVER['DOCUMENT_ROOT']."/backend/DB.php";
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/Currency.php";

if(isset($_POST['action'])){
    switch ($_POST['action']) {
        case 'create':
            create();
            break;
        default:
            header("Location: /page/currency/select.php");
            break;
    }
}
else {
    header("Location: /page/currency/select.php");
    exit();
}



function create() {
    //Проверка на пустоту поля валюты
    if(!isset($_POST['currency'])){
        header("Location: /page/currency/create.php?error=name");
        exit();
    }

    $name = $_POST['currency'];

    $Currency = new Currency();

    //Проверка на уникальность новой валюты
    {
        //Проверка на наличии уже валюты в бд с таким названием
        {
            $query = DB::сonnect()->prepare("SELECT * FROM `currency` WHERE `name` = ?");
            $query->execute([$name]);

            if ($query->fetch()) {
                header("Location: /page/currency/create.php?error=exists");
                exit();
            }
        }


        //Проверка на наличие такой валюты в глобальном списке
        {
            $currencyExists = false;

            foreach ($Currency->selectUnSavedCurrencies() as $item) {
                if ($item['name'] === $name) {
                    $currencyExists = true;
                    break;
                }
            }
            if (!$currencyExists) {
                header("Location: /page/currency/create.php?error=not_on_the_list");
                exit();
            }
        }
    }

    if ($Currency->create($name)){
        header("Location: /page/currency/select.php");
    }
    else {
        header("Location: /page/currency/create.php?error=save");
    }
    exit();
}