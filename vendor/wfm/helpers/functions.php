<?php

function debug($data, $die = false)
{
    echo '<pre>' . print_r($data, 1) . '</pre>'; // Выставляем в 1 второй параметр чтобы перехватить данные
    if ($die){
        die;
    }
}

function h($str)
{
    return htmlspecialchars($str);
}

function redirect($http = false)
{
    if ($http){ //Если передан http, мы хотим сделать редирект на конкретный адресс
        $redirect = $http; //запишем этот http
    } else{ //не передан адресс, вернуть пользователя на ту страницу с которой он пришел
        $redirect = isset($_SERVER['HTTP_REFERER']) //HTTP_REFERER - адресс с которого пользователь пришел
            ? $_SERVER['HTTP_REFERER'] // Отправить страницу с которой пришел пользователь
            : PATH; //Отправим на главную страницу
    }
    header("Location: $redirect"); //Перекидывает на страницу
    die; //завершение кода
}

function base_url()
{
    //http://new-ishop.loc/en/ если есть язык
    //http://new-ishop.loc/ если нету
    return PATH . '/' . (\wfm\App::$app->getProperty('lang')//Если присуцтвует язык
            ? \wfm\App::$app->getProperty('lang') . '/' //добавим к PATH например (en)
            : ''
        );
}


/**
 * @param string $key Ключ массива GET
 * @param string $type Значение предустановленных типов 'i' 'f' 's'
 * @return float|int|string
 */
//get('page') тип интеджер
//$_GET['page']
function get($key, $type = 'i') //получае данные из массив _GET по переданнорму $key ключу
{
    $param = $key;
    $$param = $_GET[$param] ?? ''; // $page = $_GET['page'] ?? '', запишем в $$param($page) либо $_GET[$param] если он есть либо ''
    //$$param переменная переменной, так как сюда попадает 'page' а нам нужно чтобы было $page

    if ($type == 'i'){
        return (int)$$param;
    } elseif ($type == 'f'){
        return (float)$$param;
    } else {
        return trim($$param); //trim обрежит пробелы если они есть
    }
}

/**
 * @param string $key Ключ массива POST
 * @param string $type Значение предустановленных типов 'i' 'f' 's'
 * @return float|int|string
 */

function post($key, $type = 's')
{
    $param = $key;
    $$param = $_POST[$param] ?? ''; // $page = $_GET['page'] ?? '', запишем в $$param($page) либо $_GET[$param] если он есть либо ''
    //$$param переменная переменной, так как сюда попадает 'page' а нам нужно чтобы было $page

    if ($type == 'i'){
        return (int)$$param;
    } elseif ($type == 'f'){
        return (float)$$param;
    } else {
        return trim($$param); //trim обрежит пробелы если они есть
    }
}

function __($key) //для переводных фраз
{
    echo \wfm\Language::get($key); //выводит переводную фразу
}

function ___($key)//для переводных фраз
{
    return \wfm\Language::get($key); //возращает переводную фразу
}
