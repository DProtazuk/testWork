<?php

require_once $_SERVER['DOCUMENT_ROOT']."/backend/DB.php";

class Pillow
{
    public function select(){
        $sql = "SELECT * FROM `pillow`";
        $query = DB::сonnect()->query($sql);
        return $query->fetch();
    }

    public function create($name) {
        try {
            $currentDateTime = new DateTime();
            $currentDateTimeStr = $currentDateTime->format('Y-m-d H:i:s');

            $sql = "INSERT INTO `pillow`(`name`, `data_create`, `data_update`) VALUES (?, ?, ?)";
            $query = DB::сonnect()->prepare($sql);
            $query->execute([$name, $currentDateTimeStr, null]);
            return true;
        }
        catch (PDOException $e) {
            return $e;
        }
    }
}