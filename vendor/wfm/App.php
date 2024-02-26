<?php


namespace wfm;


class App
{
    public static $app; // $app будет являтся единичным экземпляром класса Registry

    public function __construct()
    {
        $query = trim(urldecode($_SERVER['QUERY_STRING']), '/'); //Берем текущий url запрос убираем с него слеши по бокам и присваиваем. Декодирует строку url - urldecode

        new ErrorHandler(); //Создаем его чтобы он мог отлавливать ошибки
        self::$app = Registry::getInstance(); // Это по сути new Registry
        // Нам будут доступны getProperty, setProperty и тд от класса Registry
        $this->getParams(); //getParams() сама не запустится так что мы сразу вызываем ее в конструкторе она запишет знач в свойсво $properties

        Router::dispatch($query);
    }

    protected function getParams() //Параметры для подключение фреймворка(создаем параметры в config)
    {
        $params = require_once CONFIG . '/params.php'; // Запишет параметры такие как admin_email, site_name, кол-во елементов на странице
        if(!empty($params)){
            foreach ($params as $k => $v){ //$k - ключ, $v - значение
                self::$app->setProperty($k, $v); //Записываем в контейнер $properties от класса Registry
            }
        }
    }

}