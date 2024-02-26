<?php


namespace wfm;


abstract class Controller
{

    public array $data = []; // массив с данными для вида
    public array $meta = ['title' => '', 'keywords' => '', 'description' => '']; // передает из контроллера в шаблон данные страницы(заголовок, мета описание, ключевики страницы)
    public false|string $layout = ''; //будет хранится шаблон из init.php LAYOUT, false нужен для AJAX запроса(отдать какой-то вид с данными без верстки)
    public string $view = '';
    public object $model; //Для обращение к модели, автоматической загрузки данного контроллера

    public function __construct(public $route = []) //В наследуемых классах будет доступ к машруту который мы передаем аргументом в Router($controllerObject = new $controller(self::$route))
    {

    }

    public function getModel() //присваивает в переменную путь к модели и создает класс от нее
    {
        $model = 'app\models\\' . $this->route['admin_prefix'] . $this->route['controller'];// Путь к моделе
        // $this->route['admin_prefix'] для админской части если она есть, а следуящая для пользовательской
        if (class_exists($model)){ //Если такая модель сущесвует
            $this->model = new $model(); //Создать экземпляр такой модели
        }
    }

    public function getView()
    {
        $this->view = $this->view //Если мы уже что-то присвоили view
            ? //Будет присвоино это же значение
            : $this->route['action']; //Если пустая строка то присвоить значение которое в route['action']
        (new View(//передаем в конструктор
            $this->route, //путь
            $this->layout, //шаблон
            $this->view, //вид
            $this->meta //мета данные
        ))->render($this->data); //Вызываем для этого объекта метод рендер, который отрисует страницу и передаст данные
    }

    public function set($data) //Метод который складывает данные в массив
    {
        $this->data = $data; //Запишем в свойство дата(массив) все переменные который пришли
    }

    public function setMeta($title = '', $description = '', $keywords = '')
    {
        $this->meta = [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
        ];
    }


}