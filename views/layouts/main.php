<?php

use yii\helpers\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use app\assets\AppAsset;

/** @var \yii\web\View $this */
/** @var string $content */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?> - CHEEM App</title>
    <link rel="icon" type="image/x-icon" href="<?= \yii\helpers\Url::to('@web/favicon.ico') ?>">
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
    <style>
        body { background:#f4f6f7; }
        .navbar { background:#0b5d6e !important; }
        .navbar-brand, .nav-link { color:#fff !important; }
        .badge-high { background:#e67e22; }
        .badge-low { background:#27ae60; }
        .badge-medium { background:#f1c40f; color:#333; }
    </style>
</head>
<body>
<?php $this->beginBody() ?>

<nav class="navbar navbar-expand-md">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?= \yii\helpers\Url::to(['/site/index']) ?>">
            <img src="<?= \yii\helpers\Url::to('@web/images/logo.png') ?>" alt="CHEEM" style="height:36px;width:36px;border-radius:50%;object-fit:cover;margin-right:8px">
            CHEEM
        </a>
        <?php if (!Yii::$app->user->isGuest): $u = Yii::$app->user->identity; ?>
            <div class="ms-auto d-flex align-items-center">
                <?php if ($u->isAdmin()): ?>
                    <a class="nav-link d-inline me-3" href="<?= \yii\helpers\Url::to(['/user/index']) ?>">Users</a>
                    <a class="nav-link d-inline me-3" href="<?= \yii\helpers\Url::to(['/admin/services']) ?>">Services</a>
                    <a class="nav-link d-inline me-3" href="<?= \yii\helpers\Url::to(['/admin/news']) ?>">News</a>
                    <a class="nav-link d-inline me-3" href="<?= \yii\helpers\Url::to(['/suggestion/index']) ?>">Suggestions</a>
                <?php elseif ($u->isHealthOfficer()): ?>
                    <a class="nav-link d-inline me-3" href="<?= \yii\helpers\Url::to(['/admin/services']) ?>">Services</a>
                    <a class="nav-link d-inline me-3" href="<?= \yii\helpers\Url::to(['/admin/news']) ?>">News</a>
                    <a class="nav-link d-inline me-3" href="<?= \yii\helpers\Url::to(['/suggestion/index']) ?>">Suggestions</a>
                <?php else: ?>
                    <a class="nav-link d-inline me-3" href="<?= \yii\helpers\Url::to(['/suggestion/create']) ?>">Suggestions</a>
                <?php endif; ?>
                <span class="text-white me-3"><?= Html::encode($u->username) ?> (<?= Html::encode($u->role) ?>)</span>
                <?= Html::beginForm(['/site/logout'], 'post') ?>
                    <?= Html::submitButton('Logout', ['class' => 'btn btn-sm btn-outline-light']) ?>
                <?= Html::endForm() ?>
            </div>
        <?php else: ?>
            <div class="ms-auto d-flex align-items-center">
                <?= Html::a('Login', ['/site/login'], ['class' => 'btn btn-sm btn-primary me-2']) ?>
                <?= Html::a('Sign Up', ['/site/signup'], ['class' => 'btn btn-sm btn-outline-light']) ?>
            </div>
        <?php endif; ?>
    </div>
</nav>

<div class="container py-4">
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success"><?= Yii::$app->session->getFlash('success') ?></div>
    <?php endif; ?>
    <?= $content ?>
</div>

<?php $this->endBody() ?>
<script>
document.addEventListener('click', function (e) {
    var btn = e.target.closest('.toggle-password');
    if (!btn) return;
    var input = document.getElementById(btn.dataset.target);
    if (!input) return;
    if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = '🙈';
    } else {
        input.type = 'password';
        btn.textContent = '👁';
    }
});
</script>
</body>
</html>
<?php $this->endPage() ?>
