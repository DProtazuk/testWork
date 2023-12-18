<?php

require_once $_SERVER['DOCUMENT_ROOT']."/backend/DB.php";
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/Pillow.php";

if(isset($_POST['key'])){
    $key = $_POST['key'];

    $Pillow = new Pillow();
    $Pillow = $Pillow->select();

    if(empty($Pillow)){
        exit();
    }

    $idPillow = $Pillow['id'];

    if($key === "day"){
        dayAnalytics($idPillow);
    }
    if($key === "days"){
        daysAnalytics($idPillow);
    }
    if($key === "months"){
        monthsAnalytics($idPillow);
    }
}

exit();

function monthsAnalytics($idPillow) {
    $sql = "SELECT 
            DATE_FORMAT(`savings_history`.`data_update`, '%Y-%m') AS month,
            MAX(`savings_history`.`value`) AS total_value,
            `currency`.`name` AS currency_name
        FROM 
            `savings_history`
        INNER JOIN 
            `savings` ON `savings_history`.`savings_id` = `savings`.`id`
        INNER JOIN 
            `currency` ON `savings`.`currency` = `currency`.`id`
        WHERE 
            `savings`.`pillow` = ?
        GROUP BY 
            DATE_FORMAT(`savings_history`.`data_update`, '%Y-%m'), `currency`.`name`
        ORDER BY 
            DATE_FORMAT(`savings_history`.`data_update`, '%Y-%m')";

    $result = DB::сonnect()->prepare($sql);
    $result->execute([$idPillow]);
    $result = $result->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
}


function dayAnalytics($idPillow) {

    $sql = "SELECT `savings_history`.`data_update`, `savings_history`.`value`, `currency`.`name` AS currency_name
            FROM `savings_history`
            INNER JOIN `savings` ON `savings_history`.`savings_id` = `savings`.`id`
            INNER JOIN `currency` ON `savings`.`currency` = `currency`.`id`
            WHERE `savings`.`pillow` = ?
            ORDER BY `savings_history`.`data_update`
        ";

    $result = DB::сonnect()->prepare($sql);
    $result->execute([$idPillow]);
    $result = $result->fetchAll(PDO::FETCH_ASSOC);

    $data = [];

    foreach ($result as $row) {
        $currency = $row['currency_name'];
        $date = $row['data_update'];
        $value = $row['value'];

        if (!isset($data[$currency])) {
            $data[$currency] = array();
        }

        $data[$currency][] = array('date' => $date, 'value' => $value);
    }

    echo json_encode($data);
}


function daysAnalytics($idPillow) {
    $sql = "SELECT DATE(`savings_history`.`data_update`) AS day, MAX(`savings_history`.`value`) AS total_value, `currency`.`name` AS currency_name
            FROM `savings_history`
            INNER JOIN `savings` ON `savings_history`.`savings_id` = `savings`.`id`
            INNER JOIN `currency` ON `savings`.`currency` = `currency`.`id`
            WHERE `savings`.`pillow` = ?
            GROUP BY DATE(`savings_history`.`data_update`), `currency`.`name`
            ORDER BY DATE(`savings_history`.`data_update`)
        ";

    $result = DB::сonnect()->prepare($sql);
    $result->execute([$idPillow]);
    $result = $result->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
}

