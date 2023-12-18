<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/backend/Class/Pillow.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/backend/Class/Savings.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/backend/Class/History.php";

$Pillow = new Pillow();
$Savings = new Savings();
$History = new History();

$status = false;

//Проверка наличия подушки
$PillowArray = $Pillow->select();

if (!empty($PillowArray)) {
    $status = true;
}
?>

<?php
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/Currency.php";
$Currency = new Currency();
$Currency = $Currency->select();
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

    <?php
    if ($status) {
        ?>
        <div class="col-12 d-block d-md-flex flex-wrap justify-content-between my-4">
            <h4 class="text-center text-sm-start">Название подушки: <?php echo $PillowArray['name'] ?></h4>

            <a class="btn btn-primary mx-0 mx-sm-3 my-3 my-sm-0 col-12 col-sm-6 col-md-5 col-lg-4 col-xl-3 col-xxl-3 text-decoration-none" href="/page/pillow/add_savings.php">Добавить в подушку
                средства</a>

            <a class="btn btn-danger delete-pillow col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3 col-xxl-2 text-decoration-none" href="/backend/script/pillow_delete.php">Удалить подушку</a>
        </div>
    <?php } else { ?>
        <h6 class="my-3 text-center">Подушка отсутсвует...</h6>
        <form class="col-10 col-sm-9 col-md-8 col-lg-4 my-4 mx-auto" method="post" action="/backend/script/pillow.php">
            <input type="hidden" name="action" value="create">
            <input required name="pillow" type="text" class="form-control border-secondary"
                   placeholder="Введите название:">

            <button type="submit" class="btn btn-primary col-12 my-3">Создать подушку</button>

            <?php
            if (isset($_GET['error'])) {
                $error = $_GET['error'];

                if ($error === "name") {
                    echo '<h6 class="text-center text-danger">Пустое поле Подушки</h6>';
                }
                if ($error === "save") {
                    echo '<h6 class="text-center text-danger">Возникла ошибка, попробуйте еще раз</h6>';
                }
            }
            ?>
        </form>
        <?php
    }
    ?>

    <?php
    if ($status){
    ?>

    <?php
    if (!empty($Savings->select())){

    ?>

    <div class="col-12 my-2 my-sm-3 my-md-4 my-lg-5">

        <div class="col-12 col-md-6 d-flex my-4 align-content-center">
            <h6 class="my-auto">Всего накоплено:  <span class="text-danger fw-bolder span-sum-currency">0</span></h6>

            <select oninput="selectedSum.call(this)" name="currency" required class="form-select mx-3 border-secondary w-50 w-md-25" id="exampleSelect">
                <option disabled selected>Выберите В какой валюте</option>
                <?php
                    $first = true; // Дополнительная переменная для отслеживания первого элемента

                    foreach ($Currency as $item) {
                        $selected = $first ? 'selected' : ''; // Устанавливаем атрибут selected для первого элемента
                        echo '<option value="' . $item['name'] . '" ' . $selected . '>' . $item['name'] . '</option>';
                        $first = false; // Устанавливаем переменную $first в false для остальных элементов
                    }
                ?>
            </select>
        </div>

        <h5 class="fst-italic fs-6 fs-sm-5 fs-md-5 fs-lg-5">Таблица всех накопленных денег</h5>

        <div class="w-100 overflow-x-auto">
            <table class="table table table-responsive w-100 text-nowrap">
                <thead>
                <tr>
                    <th class="align-content-center">ID</th>
                    <th>Тип</th>
                    <th>Валюта</th>
                    <th>Дата создания</th>
                    <th>Дата Ред.</th>
                    <th>Сумма</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <?php
                foreach ($Savings->select() as $item) {
                    echo '<tr class="border-bottom border-secondary">
                    <td class="align-middle">'.$item['id'].'</td>
                    <td class="align-middle">'.$item['type'].'</td>
                    <td class="align-middle">'.$item['currency'].'</td>
                    <td class="align-middle">'.$item['data_create'].'</td>
                    <td class="align-middle">'.$item['data_update'].'</td>
                    <td class="align-middle">'.$item['value'].'</td>
                    <td class="align-middle">
                        <a href="/page/pillow/update.php?id='.$item['id'].'" class="btn btn-primary">Изменить</a>
                        <a href="/backend/script/savings/delete_savings.php?id='.$item['id'].'" class="btn btn-danger delete-pillow">Удалить</a>
                    </td>
                </tr>';
                }
                ?>
                </tbody>
            </table>
        </div>

        <div class="col-10 col-md-8 mx-auto d-flex mt-4 justify-content-between">
            <h6 onclick="typeChartJS.call(this)" id="day" class="h6-analytics cursor text-danger">За последний день</h6>
            <h6 onclick="typeChartJS.call(this)" id="days" class="h6-analytics cursor">По дням</h6>
            <h6 onclick="typeChartJS.call(this)" id="months" class="h6-analytics cursors">По месяцам</h6>
        </div>

        <div style="100%; height: 400px; overflow: auto;" class="w-100 overflow-x-auto overflow-y-scroll bg-secondary bg-opacity-50">
            <canvas style="min-width: 860px; min-height: 400px; max-height: 400px" id="analytics"></canvas>

        </div>

        <?php }
        }
        ?>

        <h5 class="fst-italic fs-6 fs-sm-4 fs-md-4 fs-lg-4 fs-xl-2 text-center my-3 mt-5 ">Таблица всех Изменений с подушкой</h5>

        <?php
            $History = $History->select();

            if(empty($History)){
                echo "<h6 class='text-center text-danger'>История пуста...</h6>";
            }
            else {
        ?>
        <div class="w-100 overflow-x-auto">
            <table class="table table table-responsive w-100 text-nowrap">
                <thead>
                <tr class="border-bottom border-dark">
                    <th>ID</th>
                    <th>Дата создания</th>
                    <th>Комментарии</th>
                </tr>
                </thead>
                <tbody>
                
                <?php
                    foreach ($History as $item) {
                        echo '<tr class="border-bottom border-secondary">
                                <td>'.$item['id'].'</td>
                                <td>'.$item['data_status'].'</td>
                                <td>'.$item['message'].'</td>
                             </tr>';
                    }
                
                ?>


                </tbody>
            </table>
        </div>

        <?php } ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
    <!--jquery-->
    <script src="https://code.jquery.com/jquery-3.6.1.js"
            integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.2/chart.min.js"></script>
    <script src="/js/pillow/pillow.js"></script>
</html>