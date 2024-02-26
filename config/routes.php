<?php

use wfm\Router;

Router::add('^admin/?$', ['controller' => 'Main', 'action' => 'index', 'admin_prefix' => 'admin']);
// /? - возможный слеш
// Контроллер Main, action index, но это контроллер не пользовательской части а админской будет некоторая папка где будут эти контроллеры
Router::add('^admin/(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$', ['admin_prefix' => 'admin']);
// /? перед action означает слеш не обязателен если нету action
// controller и action будут заполнены динамически

Router::add('^(?P<lang>[a-z]+)?/?product/(?P<slug>[a-z0-9-]+)/?$', ['controller' => 'Product', 'action' => 'view']);
//В url должно быть product/
//(?P<slug>[a-z0-9-]+) - означает что от а до z символы и от 1 до 9 и "-"
// '/?' - может идти слеш а может и нет, не будет иметь значения
// (?P<lang>[a-z]+)?/ - могут быть только буквы, ?/ означает что может этой приставки и не быть

Router::add('^(?P<lang>[a-z]+)?/?$', ['controller' => 'Main', 'action' => 'index']); // ^ - начало строки, $ - конец строки, controller - класс, action - метод

Router::add('^(?P<controller>[a-z-]+)/(?P<action>[a-z-]+)$');
// ?P<controller> означает здесь будет набор символов который мы запишем с ключем controller, ?P<action> тоже запишем но с ключем action
// http://new-ishop.loc/bebra/boss - в controller попадет bebra, а в action - boss
// [a-z-] - от 'a' до 'z' и '-' символы которые могут быть
// + означает что должен быть как минимум 1 символ

Router::add('^(?P<lang>[a-z]+)/(?P<controller>[a-z-]+)/(?P<action>[a-z-]+)$');
