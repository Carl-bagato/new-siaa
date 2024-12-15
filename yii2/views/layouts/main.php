<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerCssFile('@web/css/main.css', ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]);

?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- Add smooth scrolling and styling -->
    <style>
        html {
            scroll-behavior: smooth;
        }
        .navbar-light .navbar-nav .nav-link {
            color: #262626;
            transition: color 0.3s ease;
        }
        .navbar-light .navbar-nav .nav-link:hover {
            color: #086942;
        }
    </style>
</head>

<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<!-- Render the header only if the user is on the homepage (index action of site controller) -->
<?php if (Yii::$app->controller->id === 'site' && Yii::$app->controller->action->id === 'index'): ?>
    <header id="header">
        <?php
        NavBar::begin([
            'brandLabel' => Html::img('@web/images/quick_recall_logo.png', ['alt' => 'Quick Recall Logo', 'style' => 'height:25px;']),
            'brandUrl' => Yii::$app->homeUrl, // Go back to the home page when clicked
            'options' => ['class' => 'navbar navbar-expand-lg navbar-light bg-light border-bottom fixed-top'],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav ms-auto'],
            'items' => [
                ['label' => 'Home', 'url' => '#hero-section', 'linkOptions' => ['class' => 'nav-link']],
                ['label' => 'About Us', 'url' => '#about', 'linkOptions' => ['class' => 'nav-link']],
                ['label' => 'Features', 'url' => '#features', 'linkOptions' => ['class' => 'nav-link']],
                ['label' => 'Contact', 'url' => '#contact', 'linkOptions' => ['class' => 'nav-link']],
                ['label' => 'Sign Up', 'url' => ['site/signup'], 'linkOptions' => ['class' => 'btn btn-primary ms-3']],
                ['label' => 'Login', 'url' => ['/site/login'], 'linkOptions' => ['class' => 'btn btn-outline-primary ms-2']],
            ],
        ]);
        NavBar::end();
        ?>
    </header>
<?php endif; ?>

<main id="main" class="flex-shrink-0" role="main">
    <?= $content ?>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container text-center">
        <p>&copy; Quick Recall <?= date('Y') ?>. All rights reserved.</p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
