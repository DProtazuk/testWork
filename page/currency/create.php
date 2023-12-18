<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/Currency.php";
    $Currency = new Currency();
    $Currency = $Currency->selectUnSavedCurrencies();
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
    <title>Создать валюту</title>
</head>
<body>
<div class="col-11 col-md-10 mx-auto my-content">
    <?php require_once $_SERVER['DOCUMENT_ROOT']."/layout/header.php"; ?>

    <div class="my-3">
        <a class="text-decoration-none text-dark" href="/page/currency/select.php">< Назад к списку</a>
    </div>

    
    <form class="my-4 col-10 col-md-8 col-lg-6 mx-auto" action="/backend/script/currency.php" method="post">

        <h5 class="text-center">Создание валюты</h5>

        <input name="action" type="hidden" value="create">

        <label for="exampleSelect" class="form-label">Выберите новую валюту</label>
        <select name="currency" required class="form-select border-secondary" id="exampleSelect">
            <option disabled selected>Выберите из списка</option>
            <?php
            foreach ($Currency as $item) {
                echo '<option value="' . $item['name'] . '">' . $item['name'] . '</option>';
            }
            ?>
        </select>

        <button type="submit" class="col-12 my-3 btn btn-primary">Создать</button>

        <?php
            if(isset($_GET['error'])){
                $error = $_GET['error'];

                if($error === "name"){
                    echo '<h6 class="text-center text-danger">Пустое поле валюты</h6>';
                }
                if($error === "save"){
                    echo '<h6 class="text-center text-danger">Возникла ошибка, попробуйте еще раз</h6>';
                }
                if($error === "exists"){
                    echo '<h6 class="text-center text-danger">Валюта уже существует</h6>';
                }
                if($error === "not_on_the_list"){
                    echo '<h6 class="text-center text-danger">Валюты нет в списке доступных</h6>';
                }
            }
        ?>
    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</html>