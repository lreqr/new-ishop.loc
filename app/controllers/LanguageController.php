<?php


namespace app\controllers;


use wfm\App;

class LanguageController extends AppController
{
    public function changeAction() //Когда нажимаем на язык нас перебрасывает в этот action он в свою очередь обрабатывает язык на которых хотим перейти и если это английский добавляет с сылки что мы пришли en, а если на русский то убирает эту приставку поскольку у нас это базовый язык
    {
        //получаем язык на который надо перевести
        //$lang = $_GET['lang'] ?? null; //Запишем в $lang либо $_GET['lang'] если он есть, если нету то null
        //Вместо этого будет
        $lang = get('lang', 's'); //получаем из массива POST ключ lang, тип этих данных строка

        if ($lang) {
            if (array_key_exists($lang, App::$app->getProperty('languages'))){//Есть ли запорошеный язык переключения в списке доступных языков
                //отрезаем базовый URL
                $url = trim(str_replace(PATH, '', $_SERVER['HTTP_REFERER']),'/');
                //$_SERVER['HTTP_REFERER'] предыдущая страница

                //разбиваем на 2 части... 1-я часть - возможный бывший язык
                $url_parts = explode('/', $url, 2);
                //debug($url_parts);
                //ищем первую часть (бывший язык) в массиве языков
                if (array_key_exists($url_parts[0], App::$app->getProperty('languages'))){
                    //присваиваем первой части новый язык, если он не являет базовым
                    if ($lang != App::$app->getProperty('language')['code']){ //если язык полученный из _GET(en, ru) не равняет базовуму языку
                        $url_parts[0] = $lang;
                    } else {
                        // если это базовый язык - удалим язык из адресса url
                        array_shift($url_parts);
                    }
                } else {
                    //присваиваем первой части новый язык, если он не являет базовым
                    if ($lang != App::$app->getProperty('language')['code']){
                        //добавляем в начало массива $lang(язык на который хотим перейти)
                        array_unshift($url_parts, $lang);
                    }
                }
                //debug($url_parts, 1);

                $url = PATH . '/' . implode('/', $url_parts);
                //echo 'Изначальная ссылка: ' ;var_dump('http://new-ishop.loc/en/product/apple');
                //echo '<br>';
                //echo 'Ссылка после ее переделывания '; var_dump($url);die;
                redirect($url);

            }
        }
        redirect();
    }
}