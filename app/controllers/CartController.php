<?php


namespace app\controllers;


use wfm\App;
/** @property Cart $model */
class CartController extends AppController
{

    public function addAction()
    {
        $lang = App::$app->getProperty('language'); //язык получаем
        $id = get('id'); //с помощью ф-ции get получаем из массива GET знач по ключу
        $qty = get('qty');
//        var_dump($id, $qty);
        if (!$id){ //
            return false;
        }
        $product = $this->model->get_product($id, $lang);
//        debug($product, 1);
        if (!$product){
            return false;
        }

    }
}