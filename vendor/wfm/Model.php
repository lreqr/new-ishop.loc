<?php


namespace wfm;


abstract class Model //Нельзя создавать от него объект, только наследуемся
{

    public array $attributes = []; //Свойство для автозаполнения модели данными, например для форм чтобы взять данные которые нам надо
    public array $errors = []; //ошибки
    public array $rules = []; //Массив правил валидации
    public array $labels = []; //Чтобы показывать какое именно поле не прошло валидацию

    public function __construct()
    {
        Db::getInstance(); //Создаст экземпляр от класса Db
    }



}