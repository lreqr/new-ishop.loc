<?php


namespace wfm;


use RedBeanPHP\R;

class View
{

    public string $content = '';  //переменная с контентом в которая будет вставляться в шаблон

    public function __construct(
        public $route, //пути
        public $layout = '', //шаблон
        public $view = '', //
        public $meta = [],
    )
    {
        if (false !== $this->layout) { //если layout false(не хотим видеть доп верструка, для AJAX запросов)
            $this->layout = $this->layout //Если зашли сюда
                ? //Присвоить это же значение если в layout что-то есть
                : LAYOUT; //Присвоить значение LAYOUT если в layout ничего нету
        }
    }

    public function render($data)//Отрисовыввает страницу, подключает шаблон и вставляет в него вид
    {
        if (is_array($data)) { //Проверяем передали ли нам точно массив
            extract($data);//ф-ция создаст переменные на основе ключей и значений(только ассоциативный массив)
        }
        $prefix = str_replace('\\', '/', $this->route['admin_prefix']); //Меняем префикс для путей(коректно сработает)
        $view_file = APP . "/views/{$prefix}{$this->route['controller']}/{$this->view}.php"; // прописываем путь
        if (is_file($view_file)) { //Проверка если ли такой файл
            ob_start(); //Запустить буффер
            require_once $view_file;//Подключаем вид
            $this->content = ob_get_clean(); //Записываем в контент все данные из вида
        } else { //Если такого файла нету
            throw new \Exception("Не найден вид {$view_file}", 500); //Выкинуть ошибку
        }

        if (false !== $this->layout) { //Если layout false то пропустим проверку
            $layout_file = APP . "/views/layouts/{$this->layout}.php"; //путь к шаблону
            if (is_file($layout_file)) { //Есть ли файл
                require_once $layout_file; //Подключить шаблон
            } else {
                throw new \Exception("Не найден шаблон {$layout_file}", 500);
            }
        }
    }

    public function getMeta() //вернет title с 2мя тегами meta
    {
        $out = '<title>' . App::$app->getProperty('site_name') . ' :: ' . h($this->meta['title']) . '</title>' . PHP_EOL; //PHP_EOL - перенос строки для разных операционых систем
        $out .= '<meta name="description" content="' . h($this->meta['description']) . '">' . PHP_EOL;
        $out .= '<meta name="keywords" content="' . h($this->meta['keywords']) . '">' . PHP_EOL;
        return $out;
    }

    public function getDbLogs() //Получает отфильтрованные запросы бд
    {
        if(DEBUG){ //Если отладка включена
                $logs = R::getDatabaseAdapter()
                    ->getDatabase()
                    ->getLogger();
                $logs = array_merge($logs->grep('SELECT'), $logs->grep('INSERT'), $logs->grep('UPDATE'), $logs->grep('DELETE')); //фильтрует по заданым запросам
                debug($logs);
        }
    }

    public function getPart($file, $data = null) //Метод для вставки в html шаблонов шапки, сайтбара, футера
    {
        if(is_array($data)){
            extract($data); //
        }
        $file = APP . "/views/{$file}.php";
        if(is_file($file)){
            require $file; //require, потому что может понадобится этот файл вставить в нескольких местах
        } else{
            echo "File {$file} not found...";
        }
    }

}