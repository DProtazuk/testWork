<?php

require_once $_SERVER['DOCUMENT_ROOT']."/backend/DB.php";

class SavingsHistory
{
    public function create($pdo, $array){
        try {
            $sql = 'INSERT INTO `savings_history` (`savings_id`, `value`, `data_update`)
            VALUES (:savings_id, :value, :data_update)';
            $query = $pdo->prepare($sql);
            $query->execute($array);

            return true;
        }
        catch (PDOException $e) {
            return false;
        }
    }

    public function delete($pdo, $id){
        $sql = "DELETE FROM `savings_history` WHERE `savings_id` = ?";
        $query = $pdo->prepare($sql);
        $query->execute([$id]);
    }
}
