<?php

require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/Currency.php";
$Currency = new Currency();

//Список сохраненных валют
$Currency = $Currency->select();
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.min.css" rel="stylesheet"/>

    <link rel="stylesheet" href="/style/style.css">
    <title>Валюты</title>
</head>
<body>
<div class="col-11 col-md-10 mx-auto my-content">
    <?php require_once $_SERVER['DOCUMENT_ROOT']."/layout/header.php"; ?>

    <div class="col-12 d-flex justify-content-between my-4 align-items-center">
        <h4 class="text-decoration-underline">Таблица валют</h4>

        <a href="/page/currency/create.php" class="btn btn-primary text-decoration-none">Создать</a>
    </div>

    <?php
        if(empty($Currency)){
            echo '<h6>Валют пока что нет.</h6>';
        }
        else {?>
            <div class="col-12">
                <div class="d-flex col-12 border-bottom border-dark">
                    <div class="col-2">ID</div>
                    <div class="col-6">Название</div>
                    <div class="col-2"></div>
                </div>


                <?php
                    foreach ($Currency as $item){
                        echo '<div class="border-secondary border-bottom d-flex col-12 py-3 fw-bolder">
                    <div class="col-2">'.$item['id'].'</div>
                    <div class="col-6">'.$item['name'].'</div>
                    <div class="col-2"></div>
                </div>';
                    }
                ?>
            </div>
    <?php
        }
    ?>



</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</html>