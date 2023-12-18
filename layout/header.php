<?php
    //Страницы для пункта меню Подушки
    $activePagePillow = [
            '/page/pillow/pillow.php',
            '/page/pillow/add_savings.php',
            '/page/pillow/update.php'
    ];

    //Страницы для пункта меню Валюты
    $activePageCurrency = [
        '/page/currency/select.php',
        '/page/currency/create.php'
    ];

    //Текущая Страничка
    $currentFile = strtok($_SERVER['REQUEST_URI'], '?');
?>

<nav class="navbar navbar-expand-lg bg-light border-bottom border-danger">
    <div class="container-fluid">
        <a class="navbar-brand text-danger fw-bolder text-center fst-italic" href="/">Тестовое Задание</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Переключатель навигации">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse mx-4" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo (in_array($currentFile, $activePagePillow)) ? 'text-primary fw-bolder' : 'text-dark'; ?>" aria-current="page" href="/page/pillow/pillow.php">Моя подушка</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo (in_array($currentFile, $activePageCurrency)) ? 'text-primary fw-bolder' : 'text-dark'; ?>" aria-current="page" href="/page/currency/select.php">Валюты</a>
                </li>
            </ul>
        </div>
    </div>
</nav>