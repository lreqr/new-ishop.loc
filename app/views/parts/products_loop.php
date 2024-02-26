<?php /** @var $products array */ // $products мы получили методом set родительского класса Controller в MainController ?>
<?php foreach ($products as $product): ?>
    <div class="col-lg-4 col-sm-6 mb-3">
        <div class="product-card">
            <div class="product-tumb">
                <a href="product/<?= $product['slug'] //Транслитерация категории ?>"><img src="<?= PATH . $product['img'] //путь к фото ?>" alt=""></a>
            </div>
            <div class="product-details">
                <h4><a href="product/<?= $product['slug'] ?>"><?= $product['title'] //название товара(Canon EOS 5D) ?></a></h4>
                <p><?= $product['exerpt'] //краткое описание ?></p>
                <div class="product-bottom-details d-flex justify-content-between">
                    <div class="product-price">
                        <?php if ($product['old_price']): //если есть старая цена ?>
                            <small>$<?= $product['old_price'] //вывести ее ?></small>
                        <?php endif; ?>
                        $<?= $product['price'] //всегда выводить цену ?></div>
                    <div class="product-links">
                        <a class="add-to-cart" href="cart/add?id=<?= $product['id'] ?>" data-id="<?= $product['id'] ?>"><i class="fas fa-shopping-cart"></i></a>
                        <a href="#"><i class="far fa-heart"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>