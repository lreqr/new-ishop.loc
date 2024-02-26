<?php


namespace wfm;


class ErrorHandler
{

    public function __construct()
    {
        if(DEBUG){
            error_reporting(-1);
        } else{
            error_reporting(0);
        }
        set_exception_handler([$this, 'exceptionHandler']); //$this означает что обработчик будет в этом классе. Устанавливает свой обработчик для исключений

        set_error_handler([$this, 'errorHandler']); //$this означает что обработчик будет в этом классе. Устанавливает свой обработчик для ошибок

        ob_start(); // Сохраняет выводы в буфер и не выводит на экран

        register_shutdown_function([$this, 'fatalErrorHandler']); //Функция вызывается при завершении выполнении скрипта, независимо от причины завершения
    }

    public function errorHandler($errno, $errstr, $errfile, $errline)//Обработчик ошибок
    {
        $this->logError($errstr, $errfile, $errline);
        $this->displayError($errno, $errstr, $errfile, $errline);
    }

    public function fatalErrorHandler()
    {
        $error = error_get_last();
        if (!empty($error) && $error['type'] & (E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR)){
            $this->logError($error['message'], $error['file'], $error['line']);
            ob_end_clean(); // Завершает буферизацию
            $this->displayError($error['type'], $error['message'], $error['file'], $error['line']);
        } else{
            ob_end_flush(); //Завершение буферизации
        }
    }

    public function exceptionHandler(\Throwable $e) // "\" указывает на имя класса, Throwable это тип данных по типу(int, string, bool) $e это переменная в которой будет храниться экземпляр класса, производного от Throwable. $e будет хванится текст, файл в котором выброшено исключение, строка кода.
    {
        $this->logError($e->getMessage(), $e->getFile(), $e->getLine()); //принимает три аргумента: сообщение об ошибке, имя файла, в котором произошла ошибка, и номер строки

        $this->displayError('Исключение', $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode());//Вызывает displayError что запустит
    }

    protected function logError($message = '', $file = '', $line = '') //Логирование будет хранить в tmp логи
        //$message Текст ошибки, $file в котором ошибка, $line строка ошибки
    {
        file_put_contents(LOGS . '/errors.log', //путь к файлу /tmp/logs/errors.log
        "[" . date('Y-m-d H:i:s') . "] Текст ошибки: {$message} | Файл: {$file} | Строка: {$line}\n==========\n", //Красивая расспечатка
        FILE_APPEND); //FILE_APPEND для того чтобы файл не перезаписывался а дозаписывался
    }

    protected function displayError($errno, $errstr, $errfile, $errline, $responce = 500) //Показ ошибки
        //$errno номер ошибки,$errstr строка ошибки, $errfile в котором ошибка, $errline строка ошибки, $responce код ответа
    {
        if($responce == 0){ //Если придет 0 то отправим 404
            $responce = 404;
        }
        http_response_code($responce); //HTTP-статуса ответа на сервер 404, 500 и тд
        if($responce == 404 && !DEBUG){ //Если респонс понятно, и выключена отладка ошибок
            require WWW . '/errors/404.php'; //Создали папку errors файл с ответом 404
            die;
        }
        if(DEBUG){
            require WWW . '/errors/development.php'; // Показывает полностью описание ошибки
        } else{
            require WWW . '/errors/production.php'; //
        }
        die; //В любом случае завершаем работу скрипта
    }
}