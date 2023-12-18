<?php

require_once $_SERVER['DOCUMENT_ROOT']."/backend/DB.php";

class Savings
{
    public function create($pdo, $array) {
        try {
            $db = $pdo;
            $sql = "INSERT INTO `savings`(`pillow`, `type`, `currency`, `data_create`, `data_update`, `value`) 
                VALUES (:pillow, :type, :currency, :data_create, :data_update, :value)";
            $query = $db->prepare($sql);
            $query->execute($array);
            // Получение id вставленной записи
            return $db->lastInsertId();
        }
        catch (PDOException $e) {
            return false;
        }
    }

    public function select() {
        $sql = "SELECT
            `savings`.`id` as id,
            `savings_type`.`name` as type,
            `currency`.`name` as currency,
            `savings`.`data_create` as data_create,
            `savings`.`data_update` as data_update,
            `savings`.`value` as value
            
            FROM `savings` 
                INNER JOIN `savings_type` ON `savings`.`type` = `savings_type`.`id`
                INNER JOIN `currency` ON `savings`.`currency` = `currency`.`id`
        ";

        $query = DB::сonnect()->query($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectOneToId($id){
        $sql = "SELECT * FROM `savings` WHERE `id` = ?";
        $query = DB::сonnect()->prepare($sql);
        $query->execute([$id]);
        return $query->fetch();
    }

    public function delete($pdo, $id) {
        $sql = "DELETE FROM `savings` WHERE `id` = ?";
        $query = $pdo->prepare($sql);
        $query->execute([$id]);
    }

    public function selectPillowToUpdate($id) {
        $sql = "SELECT
            `savings`.`id` as id,
            `savings`.`pillow` as pillow,
            `savings_type`.`id` as type,
            `currency`.`id` as currency,
            `savings`.`value` as value
            
            FROM `savings` 
                INNER JOIN `savings_type` ON `savings`.`type` = `savings_type`.`id`
                INNER JOIN `currency` ON `savings`.`currency` = `currency`.`id`
            WHERE `savings`.`id` = ?
        ";

        $query = DB::сonnect()->prepare($sql);
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function update($pdo, $array) {
        try {
            $sql = "UPDATE `savings` SET 
                     `type`= :type,
                     `data_update`= :data_update, 
                     `value`= :value 
                 WHERE `id` = :id";
            $query = $pdo->prepare($sql);
            $query->execute($array);
            return true;
        }
        catch (PDOException $e) {
            return false;
        }
    }
}