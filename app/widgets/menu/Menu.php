<?php

namespace app\widgets\menu;

use RedBeanPHP\R;
use wfm\App;
use wfm\Cache;


class Menu
{

    protected $data; //категории из бд
    protected $tree; // сформированное дерево из данных из бд
    protected $menuHtml; //html код сформированного меню
    protected $tpl; //шаблон который можем переопределить
    protected $container = 'ul'; // обертка для нашего меню
    protected $class = 'menu'; // используется для тега нешего меню по умолчанию 'menu'
    protected $table = 'category';// таблица в бд
    protected $cache = 3600; //кол-во сек на которое будет кешироваться наше меню
    protected $cacheKey = 'ishop-menu'; //ключ по котором данные будут кешироваться
    protected $attrs = [];// атрибуты которые можем добавить в меню data, id
    protected $prepend = '';// свойство которое можно добавить перед меню
    protected $language; //язык активный

    public function __construct($options = [])
    {
        $this->language = \wfm\App::$app->getProperty('language'); //получаем текущий язык
        $this->tpl = __DIR__ . '/menu_tpl.php'; //шаблон
        $this->getOptions($options);
        $this->run();

    }

    protected function getOptions($options){
        foreach ($options as $k => $v){
            if (property_exists($this, $k)){
                $this->$k = $v;
            }
        }
    }

    protected function run(){
        $cache = Cache::getInstance();
        $this->menuHtml = $cache->get("{$this->cacheKey}_{$this->language['code']}");

        if(!$this->menuHtml){
            $this->data = R::getAssoc("SELECT c.*, cd.* FROM category c 
                        JOIN category_description cd
                        ON c.id = cd.category_id
                        WHERE cd.language_id = ?", [$this->language['id']]);
            $this->tree = $this->getTree();
            $this->menuHtml = $this->getMenuHtml($this->tree);
            if($this->cache){
                $cache->set("{$this->cacheKey}_{$this->language['code']}", $this->menuHtml, $this->cache);
            }
        }

        $this->output();
    }

    protected function output(){
        $attrs = '';
        if(!empty($this->attrs)){
            foreach($this->attrs as $k => $v){
                $attrs .= " $k='$v' ";
            }
        }
        echo "<{$this->container} class='{$this->class}' $attrs>";
        echo $this->prepend;
        echo $this->menuHtml;
        echo "</{$this->container}>";
    }

    protected function getTree(){
        $tree = [];
        $data = $this->data;
        foreach ($data as $id=>&$node) {
            if (!$node['parent_id']){
                $tree[$id] = &$node;
            } else {
                $data[$node['parent_id']]['children'][$id] = &$node;
            }
        }
        return $tree;
    }

    protected function getMenuHtml($tree, $tab = ''){
        $str = '';
        foreach($tree as $id => $category){
            $str .= $this->catToTemplate($category, $tab, $id);
        }
        return $str;
    }

    protected function catToTemplate($category, $tab, $id){
        ob_start();
        require $this->tpl;
        return ob_get_clean();
    }

}