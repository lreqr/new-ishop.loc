<?php
use wfm\View;
/** @var $this View */
?>

<?= $this->getPart('/parts/header') ?>

<?= $this->content ?>

<?= $this->getPart('/parts/footer') ?>
