<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/backend/Class/Pillow.php";

$Pillow = new Pillow();

//Проверка наличия подушки
{
    $PillowArray = $Pillow->select();

    //Не переходить на эту страничку
    if (empty($PillowArray)) {
        header("Location: /page/pillow/pillow.php");
        exit();
    }
}


//Проверка существования данных денег и закрепление к подушке
{
    if(!isset($_GET['id'])){
        header("Location: /page/pillow/pillow.php");
        exit();
    }

    $id = $_GET['id'];

    require_once $_SERVER['DOCUMENT_ROOT'] . "/backend/Class/Savings.php";
    $Savings = new Savings();
    $Savings = $Savings->selectPillowToUpdate($id);

    if(empty($Savings)){
        header("Location: /page/pillow/pillow.php");
        exit();
    }

    if($PillowArray['id'] !== $Savings['pillow']){
        header("Location: /page/pillow/pillow.php");
        exit();
    }
}


//Доступыные валюты
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/Currency.php";
$Currency = new Currency();
$Currency = $Currency->select();

//Доступыные типы денег
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/SavingsType.php";
$SavingsType = new SavingsType();
$SavingsType = $SavingsType->select();

?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/style/style.css">
    <title>Моя подушка</title>
</head>
<body>
<div class="col-11 col-md-10 mx-auto my-content">
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/layout/header.php"; ?>

    <div class="my-3">
        <a class="text-decoration-none text-dark" href="/page/pillow/pillow.php">< Назад к списку</a>
    </div>

    <form class="col-10 col-sm-8 col-md-8 col-lg-4 mx-auto" action="" id="myForm">
        <h6 class="text-center text-primary text-decoration-underline d-none h6-update">Изменения сохранены</h6>

        <h5 class="text-center">Изменение денег</h5>

        <input name="action" type="hidden" value="create">

        <label for="exampleSelect" class="form-label mt-2">Выберите тип накопления</label>
        <select name="savings_type" required class="form-select border-secondary bg-opacity-50" id="exampleSelect">
            <option disabled selected>Выберите из списка</option>

            <?php
            foreach ($SavingsType as $item) {
                if($item['id'] === $Savings['type']){
                    echo '<option selected value="'.$item['id'].'">'.$item['name'].'</option>';
                }
                else echo '<option value="'.$item['id'].'">'.$item['name'].'</option>';
            }
            ?>
        </select>

        <label class="form-label mt-4">Введите сумму (Необязятельно)</label>
        <input value="<?php echo $Savings['value'] ?>" type="text" name="value" class="form-control border-secondary bg-opacity-50" placeholder="Сумма:">
        <button class="btn btn-primary my-3 col-12">Изменить накопление</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
<script src="/js/pillow/update.js"></script>
</html>