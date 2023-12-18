<?php

require_once $_SERVER['DOCUMENT_ROOT']."/backend/DB.php";
require_once $_SERVER['DOCUMENT_ROOT']."/backend/Class/Currency.php";

class Currency
{

    //Выборка всех валют и преобразование в массив с ключами
    public function selectApiCurrency() {
        try {
            // URL запроса к API для получения актуальных курсов валют
            $url = "https://api.exchangerate-api.com/v4/latest/USD";

            // Получаем данные с помощью функции file_get_contents и декодируем JSON-ответ
            $response = file_get_contents($url);
            $data = json_decode($response, true);
            $data = $data['rates'];

            // Создаем новый массив с нужной структурой
            $result = [];
            foreach ($data as $currency => $rate) {
                $result[] = [
                    'name' => $currency,
                    'value' => $rate
                ];
            }



            return $result;
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    //Выборка всех валют исключая те что уже сохранены
    public function selectUnSavedCurrencies() {
        //Спикок всех валют
        $selectApiCurrency = $this->selectApiCurrency();

        //Список сохраненный валют
        $savedCurrencies = $this->select();


        //Если есть сохраненные валюты то удаляем их с массива
        if(!empty($savedCurrencies)){

            //Собираем ключи name
            $namesToRemove = array_column($savedCurrencies, 'name');

            //Создаем новый массив и помещаем туда все валюты кроме сохраненных
            $result = [];
            foreach ($selectApiCurrency as $item) {
                if (!in_array($item['name'], $namesToRemove)) {
                    $result[] = $item;
                }
            }
            return $result;
        }
        //Если нет то отправляем все валюты
        else return $selectApiCurrency;

    }

    //Создание новой валюты
    public function create($name) {
        try {
            $sql = "INSERT INTO `currency`(`name`) VALUES (?)";
            $query = DB::сonnect()->prepare($sql);
            $query->execute([$name]);
            return true;
        }
        catch (PDOException $e) {
            return false;
        }
    }

    //Выборка всех сохараненных валют
    public function select(){
        $sql = "SELECT * FROM `currency`";
        $query = DB::сonnect()->query($sql);
        return $query->fetchAll();
    }

    //Выборка всех сохараненных валют
    public function selectID($id){
        try {
            $sql = "SELECT * FROM `currency` WHERE `id` = ?";
            $query = DB::сonnect()->prepare($sql);
            $query->execute([$id]);
            return $query->fetch();
        }
        catch (PDOException $e) {
            return false;
        }
    }

    //Выборка всех сохараненных валют
    public function selectName($name){
        try {
            $sql = "SELECT * FROM `currency` WHERE `name` = ?";
            $query = DB::сonnect()->prepare($sql);
            $query->execute([$name]);
            return $query->fetch();
        }
        catch (PDOException $e) {
            return false;
        }
    }

}