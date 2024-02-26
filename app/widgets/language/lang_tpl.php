<div class="dropdown d-inline-block">
    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
        <img src="<?= PATH ?>/assets/img/lang/<?= \wfm\App::$app->getProperty('language')['code'] //из массива текущего языка получить его код ?>.png" alt="">
    </a>
    <ul class="dropdown-menu" id="languages">
        <?php foreach ($this->languages as $k => $v): ?>
            <?php if(\wfm\App::$app->getProperty('language')['code'] == $k) continue; //Если из массива текущего языка code(ru, en) будет равен ключу то пропустить ?>
            <li>
                <button class="dropdown-item" data-langcode="<?= $k ?>">
                    <img src="<?= PATH ?>/assets/img/lang/<?= $k ?>.png" alt="">
                    <?= $v['title'] //English или Русский ?></button>
            </li>
        <?php endforeach; ?>
    </ul>
</div>