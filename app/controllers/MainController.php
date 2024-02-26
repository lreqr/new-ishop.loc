<?php

namespace app\controllers;

use app\widgets\language\Language;
use RedBeanPHP\R;
use app\models\Main;
use wfm\App;
use wfm\Cache;
use wfm\Controller; //импорт пространства имен (namespace) или конкретного класса в текущий файл или класс.
/** @property Main $model */
class MainController extends AppController
{

    //public false|string $layout = 'test2'; //Переопредиляем шаблон для всех action

    public function indexAction()
    {
        $test = 'Hello';
        $cache = Cache::getInstance();
        //$cache->set('test', $test, 5);
        //var_dump($cache->get('test'));
        //die;
        //var_dump($test);
        $lang = App::$app->getProperty('language'); //Получаем язык,
        $slides = R::findAll('slider'); //Запишет все записи из таблицы slider

        $products = $this->model->get_hits($lang, 6); //получаем массив с 3мя массивами с полной информацией про товар id, category_id, slug, price, old_price, status, hit, img, is_download, product_id, language_id, title, content, exerpt, keywords, description

        //debug($products, 1);
        $this->set(compact('slides', 'products')); //Передаем переменную slider в View, compact сделает переменные на основе ключ значение
        $this->setMeta(___('main_index_meta_title'),  ___('main_index_meta_description'), ___('main_index_meta_keywords'));


    }
}