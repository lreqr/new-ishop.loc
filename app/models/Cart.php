<?php


namespace app\models;


use RedBeanPHP\R;

class Cart extends AppModel
{

    public function get_product($id, $lang):array
    {
        //getRow возвращает ассоциативный массив . Используется, когда нужно получить только одну строку данных, например, информацию о конкретном объекте
        return R::getRow("SELECT p.*, pd.* FROM product p JOIN product_description pd on p.id = pd.product_id WHERE p.status = 1 AND p.id = ? AND pd.language_id = ?", [$id, $lang['id']]);
    }

    public function add_to_cart($product, $qty = 1)
    {
        $qty = abs($qty); //abs возвращает число без знака

        if ($product['is_download'] && isset($_SESSION['cart'][$product['is_download']])){
            return false;
        }

        if (isset($_SESSION['cart'][$product['id']])){

        }
    }
}
/*Array
(
    [product_id] => Array
        (
            [qty] => QTY
            [title] => TITLE
            [price] => PRICE
            [img] => IMG
        )
    [product_id] => Array
        (
            [qty] => QTY
            [title] => TITLE
            [price] => PRICE
            [img] => IMG
        )
    )
    [cart.qty] => QTY,
    [cart.sum] => SUM
*/
