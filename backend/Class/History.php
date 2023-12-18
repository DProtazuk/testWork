<?php

require_once $_SERVER['DOCUMENT_ROOT']."/backend/DB.php";

class History
{
    public function create($array) {
        try {
            $sql = "INSERT INTO `history`(`data_status`, `message`) 
                VALUES (:data_status, :message)";
            $query = DB::сonnect()->prepare($sql);
            $query->execute($array);

        }
        catch (PDOException $e) {
            return false;
        }
    }

    public function select() {
        $sql = "SELECT * FROM `history` ORDER BY `data_status` DESC";
        $query = DB::сonnect()->query($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
