<?php


namespace wfm;


class Cache
{
    use TSingleton;

    //$key - ключ по которому запишем данные
    //$data - данные которые запишем
    //$seconds - кол-во сек на которые запишем 3600(1 час)
    //возращает t/f удалось записать или нет
    public function set($key, $data, $seconds = 3600): bool
    {
        $content['data'] = $data;
        $content['end_time'] = time() + $seconds;

        //file_put_contents записывает данные в файл, 1 Аргумент путь к файлу(filename), 2 Аргументом данные которые запишет
        //1 аргумент будет /vendor/tmp/cache(CACHE)/хеш ключа $key.txt
        //2 аргумент serialize переобразовывает php-знач переменные, массивы и тд. в строку чтобы легче можно было сохранить в файле
        if (file_put_contents(CACHE . '/' . md5($key) . '.txt', serialize($content))){
            return true;
        } else{
            return false;
        }

    }

    public function get($key)//получает данные из кеша
    {
        $file = CACHE . '/' . md5($key) . '.txt';
        if (file_exists($file)){
            //так как $file серелизованные нужно его десерилизовать unserialize
            //file_get_contents - используется для чтения содержимого файла и его возврата в виде строки
            $content = unserialize(file_get_contents($file));
            //если текущее время меньше того что мы записали вернуть эти же данные
            if (time() <= $content['end_time']){
                return $content['data'];
            } else{ //в противном случае удалить
                unlink($file);
            }
        }
        return false;
    }

    public function delete($key)//удаляет данные из кеша
    {
        $file = CACHE . '/' . md5($key) . '.txt';
        if (file_exists($file)){
            unlink($file);
        }
    }

}