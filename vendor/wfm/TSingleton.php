<?php


namespace wfm;


trait TSingleton //Шаблон для других классов
{
    private static ?self $instance = null; //?self $instance = null принимает значение себя либо null

    private function __construct(){} // Гарантирует что через new никто не сможет создать экземпляр

    public static function getInstance(): static // Создает экземпляр класса
    {
        return static::$instance ?? static::$instance = new static();// Возвращает объект либо создает новый экземпляр класса
    }//$instance существует для проверки чтобы гарантированно создать 1 экземпляр класса
}