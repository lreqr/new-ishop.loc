<?php

namespace app\widgets\language;

use RedBeanPHP\R;
use wfm\App;

class Language
{

    protected $tpl; //В нем будет хранится шаблон, который будет реализовывать данный виджет(html код, внешний вид)
    protected $languages; //Будут хранится все языки
    protected $language; //Текущий, активный язык


    public function __construct() //Инициализация виджета, тут можем переопределить шаблон если захотим
    {
        $this->tpl = __DIR__ . '/lang_tpl.php'; //определяем путь к шаблону
        $this->run(); //Записывает в свойста языки и текщий язык, и вызывает шаблон
    }

    public function run() //Будем получать $languages и $language
    {
        $this->languages = App::$app->getProperty('languages');
        $this->language = App::$app->getProperty('language');
        echo $this->getHtml(); //Вызывает шаблон
    }

    public static function getLanguages() //Языки , получаем массив с 2 ключами ru и en, у них 3 ключа title, base, id
    {
        //Получение массива языков из бд
        return R::getAssoc("SELECT code, title, base, id FROM language ORDER BY base DESC"); //getAssoc возращает ассоциативный массив при этом в качестве ключей буду те поля которые переданы первыми, в данном случае code будет ключем

    }

    public static function getLanguage($languages) //Будет получать языки, сравнивать с языком пользователя и записывать это значение в $language
    {
        $lang = App::$app->getProperty('lang'); //Присваиваем массив с языками
        if ($lang && array_key_exists($lang, $languages)){ //Если язык и ключ в массиве языков такой есть
            $key = $lang; //пример en
        } elseif (!$lang){ //Если не язык, подразумивается язык по умолчанию
            $key = key($languages); //key берет текущий ключ массива, по этому и делали сортировку чтобы первым был язык тот что по умолчанию
        } else {
            $lang = h($lang);
            throw new \Exception("Not found language {$lang}", 404);
        }
        //var_dump($key); // string(2) "ru" если без приставки в ссылке, с приставкой en получаем string(2) "en"
        //die;

        $lang_info = $languages[$key]; //$key либо ru либо en, а знач по этим ключам это ассациативный массив
        $lang_info['code'] = $key; //запишем код потому что сверху мы только значение этого $key записали, а не самого его
        return $lang_info; //[title] => Русский [base] => 1 [id] => 1 [code] => ru
    }

    protected function getHtml(): string
    {
        ob_start(); //Включает буферизацию
        require_once $this->tpl; //этот шаблон запишется в буфер
        return ob_get_clean(); //вернет буфер
    }

}