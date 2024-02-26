<?php


namespace wfm;


class Language
{
    //массив со всеми переводными фразами страницы(переводные фразы шаблона и вида)
    public static array $lang_data = [];
    //массив со всеми переводными фразами шаблона
    public static array $lang_layout = [];
    //массив со всеми переводными фразами вида
    public static array $lang_view = [];

    //$code код языка, $view - $route(марщрут)
    public static function load($code, $view) //загружает переводные фразы для 3х массивов $lang_...
    {
        $lang_layout = APP . "/languages/{$code}.php"; //Пример /languages/ru.php для русского языка
        $lang_view = APP . "/languages/{$code}/{$view['controller']}/{$view['action']}.php"; //Пример /languages/en/Main/index.php для главной страницы англ языка
        if (file_exists($lang_layout)){
            self::$lang_layout = require_once $lang_layout;
        }
        if (file_exists($lang_view)){
            self::$lang_view = require_once $lang_view;
        }
        self::$lang_data = array_merge(self::$lang_layout, self::$lang_view);
    }

    public static function get($key) //по ключу возвращет переводную фразу
    {
        return self::$lang_data[$key] ?? $key; //Если есть такой ключ $key в массиве $lang_data то вернуть его, если нету то вернуть передаваемый ключ
    }
}