<?php


namespace wfm;


class Registry
{
    use TSingleton;

    protected static array $properties = [];

    public function setProperty($name, $value) //Сеттер
    {
        self::$properties[$name] = $value; //запишет в $properties индекс и значение
    }

    public function getProperty($name) //Геттер
    {
        return self::$properties[$name] ?? null; //Вернет либо значение либо null чтобы не было ошибки
    }

    public function getProperties(): array
    {
        return self::$properties; //Возращает массив $properties
    }

}