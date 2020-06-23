<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="none">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode(Yii::t('dotenv', 'title')) ?></title>
    <?php $this->head() ?>
</head>
<body>
<div class="page-container">
    <?php $this->beginBody() ?>
    <div class="container content-container">
        <?= $content ?>
    </div>
    <div class="footer-fix"></div>
</div>
<footer class="footer border-top">
    <div class="container">
        <div class="row">
            <div class="col-6">
                <p>A Product of <a href="http://www.yiisoft.com/">Yii Software LLC</a></p>
            </div>
            <div class="col-6">
                <p class="text-right"><?= Yii::powered() ?></p>
            </div>
        </div>
    </div>
</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
