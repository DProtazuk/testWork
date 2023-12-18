<?php

class DB
{
    static function сonnect()
    {
        $dsn = 'mysql:host=localhost;dbname=test';
        $username = 'root';
        $password = '';

        $dsnHost = 'mysql:host=localhost;dbname=dimaprtr_test';
        $usernameHost = 'dimaprtr_test';
        $passwordHost = '*81oEBcK';


        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            return new PDO($dsn, $username, $password,$options);
        } catch (PDOException $e) {
//            die($e->getMessage());
            return new PDO($dsnHost, $usernameHost, $passwordHost,$options);
        }
    }
}