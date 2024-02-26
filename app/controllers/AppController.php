<?php


namespace app\controllers;


use app\models\AppModel;
use app\widgets\language\Language;
use wfm\App;
use wfm\Controller;
//AppController разширяет Controller фреймворка
class AppController extends Controller
{

    public function __construct($route)
    {
        parent::__construct($route); //Вызывается конструктор родительского класса Controller

        new AppModel();// Решение ошибки отсуцтвия модели

        App::$app->setProperty('languages', Language::getLanguages()); //получаем массив с 2 ключами ru и en, у них 3 ключа title, base, id
        App::$app->setProperty('language', Language::getLanguage(App::$app->getProperty('languages'))); //Записать текущий язык в $app, getLanguage принимает массив языков и возвращает текущий

        $lang = App::$app->getProperty('language');
        \wfm\Language::load($lang['code'], $this->route);
        //debug($this->route);
        //debug(\wfm\Language::$lang_data, 1);
    }

}