<?php


namespace wfm;

use RedBeanPHP\R;

class Db
{
    use TSingleton; //От этого класса можно будет создать только 1 объект

    private function __construct()//Нельзя использовать new
    {
        $db = require_once CONFIG . '/config_db.php'; //Вернуло массив с данными для субд

        R::setup($db['dsn'], $db['user'], $db['password']);//фунуция для подключения бд в RedBeanPHP

        if (!R::testConnection()) { //Проверяет подключились ли мы к бд
            throw new \Exception("No connection to DB",500);
        }
        R::freeze(true);//Наша библиотека позволяет на лету менять таблички, модифицировать их
        //Нам это не надо поэтому мы freeze выставили в true

        if(DEBUG){ //Только если включена константа DEBUG(то есть не продакшн)
            R::debug(true, 3); //Возвращает sql запросы которые будет выполнять, собирает в массив и возвращает
        }
    }
}