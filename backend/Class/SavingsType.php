<?php

require_once $_SERVER['DOCUMENT_ROOT']."/backend/DB.php";

class SavingsType
{
    public function select(){
        $sql = "SELECT * FROM `savings_type`";
        $query = DB::сonnect()->query($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}