<?php


namespace wfm;

class Router
{
    protected static array $routes = []; //Свойство это табл маршрутов
    protected static array $route = []; //Свойство, попадает конкретный 1 маршрут с которым было найдено соотвествие

    public static function add($regexp, $route = []) //$regexp - шаблон регулярного выражения, $route - контроллер(класс) и экшен(метод) который нужно соотнести с регулярным выражением.
    {
        self::$routes[$regexp] = $route; //В таблицу $routes(маршрутов) по ключу $regexp(регулярное выражение) присвоим $route(контроллер и экшен или [])
    }

    public static function getRoutes(): array
    {
        return self::$routes;
    }

    public static function getRoute(): array
    {
        return self::$route;
    }

    protected static function removeQweryString($url) //Убирает GET параметры с url, чтобы коректно обработать url(мы все еще имеет доступ к GET параметрам). Страница с GET параметрами не найдет свой контроллер, поэтому мы их убрали(параметры)
    {
        if ($url) { //Не сработате в случае если мы на главной странице а это пустая строка

            $params = explode('&', $url, 2); //Разбивает url запрос по разделителю & с лимитом 2 то есть будет 2 елемента массива [0] => page/view, [1] => id=123&test=test

            if (false === str_contains($params[0], '=')){ //Если нету в 0 индексе '='
                return rtrim($params[0], '/'); //Вернуть туже строку но там может быть слеш поэтому мы его обрезаем
            }

        }
        return ''; //Если пустая строка вернуть ее поскльку это главная страница
    }

    public static function dispatch($url)
    {
        $url = self::removeQweryString($url); //Убирает GET параметры с url, чтобы коректно обработать url

        if (self::matchRoute($url)){ //matchRoute сравнивает запрос с регулярным выражение возвращает bool
            //debug(self::$route, 1); //в ключ lang попадает приставка языка адрессной строки

            if (!empty(self::$route['lang'])){ //Проверка на наличие языка в адрессной строке
                App::$app->setProperty('lang', self::$route['lang']); //Если такая есть то добавить это знач
            }



            $controller = 'app\controllers\\' . self::$route['admin_prefix'] . self::$route['controller'] . 'Controller';
            //Админ: путь к контроллеру app\controllers слеш admin и наименование контроллера
            //Обычные пользователи: app\controllers пустая строка(нету знач админа) и контроллер
            // Добавим постфикс Controller, потому что могут быть вспомогательные классы которые не будем вызывать

            if (class_exists($controller)) { // Если существует такой контроллер

                /** @var Controller $controllerObject */ //Этим мы сказали что $controllerObject является обектом класса Controller
                $controllerObject = new $controller(self::$route); //Создами экземпляр класса $controller

                $controllerObject->getModel();//присваивает в переменную путь к модели и создает класс от нее

                $action = self::lowerCamelCase(self::$route['action'] . 'Action');
                //пример: indexAction; именно он будет искаться в данном объекте $controller

                if (method_exists($controllerObject, $action)){
                    $controllerObject->$action(); //Вызывается какой-то экшен из $route

                    $controllerObject->getView(); //Вызываем view тут потому что его нужно вызвать после action потому что мы его можем сами переопредилить

                } else{
                    throw new  \Exception("Метод {$controller}::{$action} не найден", 404);
                    //Пример: PageController::view (ненайдено)
                }
            } else{
                throw new  \Exception("Контроллер {$controller} не найден", 404);
            }
        } else{
            throw new  \Exception("Страница не найдена", 404);
        }
    }

    public static function matchRoute($url):bool//Сравнивает текущий запрос с шаблоном регулярного выражения(с табл. маршрутов в $routes)
    {
        foreach (self::$routes as $pattern => $route) //В $pattern попадает ^admin/?$, а в $route [controller] => Main [action] => index [admin_prefix] => admin массив и так далее пол массиву $routes
        {
            if(preg_match("#{$pattern}#", $url, $matches))//preg_match Возвращает 1 или 0, проверяет на соответсвие регулярному выражению
            {
//                debug($route);// пустой массив
//                debug($matches);// массив того что записали в url
                foreach ($matches as $k => $v){ //перебираем массив
                    if (is_string($k)){ //Если это строка
                        $route[$k] = $v; //Записывает в массив $route
                        }
                    }
                    if (empty($route['action'])){ //Добавление что если отсутсвует action присвоить ему index
                        $route['action'] = 'index';
                    }
                    if (!isset($route['admin_prefix'])){ //Для работы с админкой
                        $route['admin_prefix'] = '';
                    } else{
                        $route['admin_prefix'] .= '\\'; //Берем то что в admin_prefix и добавляем через .=
                        // \\ интерпритируются как одна '\'
                    }
                    $route['controller'] = self::upperCamelCase($route['controller']); //Записываем в кемел кейс строку нашего контроллера
                    self::$route = $route;
                    //debug($route); //[controller] => Page [action] => view [admin_prefix] =>
                    return true;
            }
        }
        return false;
    }

    //CamelCase
    protected static function upperCamelCase($name): string
    {
        // new-product => new product
        $name = str_replace('-', ' ', $name);
        // new product => New Product
        $name = ucwords($name);
        // New Product => NewProduct
        return str_replace(' ', '', $name);
    }

    //camelCase
    protected static function lowerCamelCase($name): string
    {
        return lcfirst(self::upperCamelCase($name));
        //внутри скобок CamelCase, lcfirst() сделает из этого camelCase
    }
}