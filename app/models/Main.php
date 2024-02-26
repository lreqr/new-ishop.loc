<?php

namespace app\models;

use RedBeanPHP\R;

class Main extends AppModel
{

    public function get_hits($lang, $limit): array //
    {
        return R::getAll("SELECT p.* , pd.* FROM product p JOIN product_description pd on p.id = pd.product_id WHERE p.status = 1 AND p.hit = 1 AND pd.language_id = ? LIMIT $limit", [$lang['id']]); //Выбрать все из таблиц product и product_description, где мы соединияем(JOIN) эти 2 таблицы где p.id = pd.product_id, где(WHERE) p.status = 1 и(AND) p.hit = 1 и(AND) d.language_id = ? и лимит(LIMIT) продуктов нами переданный. [$lang] значение будет подставлено вместо ?
    }
}