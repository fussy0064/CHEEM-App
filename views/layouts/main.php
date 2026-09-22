<?php

use yii\helpers\Html;
use yii\helpers\Url;
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
    <link rel="icon" type="image/x-icon" href="<?= Url::to('@web/favicon.ico') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zilla+Slab:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<nav class="navbar navbar-expand-md navbar-cheem" aria-label="Main navigation">
    <div class="container">
        <a class="navbar-brand" href="<?= Url::to(['/site/index']) ?>">
            <img src="<?= Url::to('@web/images/logo.png') ?>" alt="CHEEM logo" class="logo-badge" width="32" height="32">
            CHEEM
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation"
                style="border-color: #ffffff;">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <?php if (!Yii::$app->user->isGuest): $u = Yii::$app->user->identity; ?>
                <ul class="navbar-nav ms-auto align-items-md-center">
                    <?php if ($u->isAdmin()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-users"></i> Users
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?= Url::to(['/user/index']) ?>"><i class="fas fa-list"></i> View All</a></li>
                                <li><a class="dropdown-item" href="<?= Url::to(['/user/create']) ?>"><i class="fas fa-plus"></i> Add User</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if ($u->isAdmin() || $u->isHealthOfficer()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-briefcase-medical"></i> Services
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?= Url::to(['/admin/services']) ?>"><i class="fas fa-list"></i> View All</a></li>
                                <li><a class="dropdown-item" href="<?= Url::to(['/admin/service-create']) ?>"><i class="fas fa-plus"></i> Add Service</a></li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-newspaper"></i> News
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?= Url::to(['/admin/news']) ?>"><i class="fas fa-list"></i> View All</a></li>
                                <li><a class="dropdown-item" href="<?= Url::to(['/admin/news-create']) ?>"><i class="fas fa-plus"></i> Add News</a></li>
                            </ul>
                        </li>

                        <li class="nav-item"><a class="nav-link" href="<?= Url::to(['/suggestion/index']) ?>"><i class="fas fa-comment-dots"></i> Suggestions</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="<?= Url::to(['/suggestion/create']) ?>"><i class="fas fa-comment-dots"></i> Suggestions</a></li>
                    <?php endif; ?>

                    <li class="nav-item d-flex align-items-center my-2 my-md-0 mx-md-3">
                        <span class="text-white small"><?= Html::encode($u->username) ?> &middot; <?= Html::encode(str_replace('_', ' ', $u->role)) ?></span>
                    </li>
                    <li class="nav-item">
                        <?= Html::beginForm(['/site/logout'], 'post') ?>
                            <?= Html::submitButton('Logout', ['class' => 'btn btn-sm btn-outline-light']) ?>
                        <?= Html::endForm() ?>
                    </li>
                </ul>
            <?php else: ?>
                <ul class="navbar-nav ms-auto align-items-md-center gap-md-2">
                    <li class="nav-item"><?= Html::a('Login', ['/site/login'], ['class' => 'btn btn-sm btn-primary my-1 my-md-0']) ?></li>
                    <li class="nav-item"><?= Html::a('Sign Up', ['/site/signup'], ['class' => 'btn btn-sm btn-outline-light my-1 my-md-0']) ?></li>
                </ul>
            <?php endif; ?>
        </div>
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
    var icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        if (icon) { icon.classList.remove('fa-eye'); icon.classList.add('fa-eye-slash'); }
    } else {
        input.type = 'password';
        if (icon) { icon.classList.remove('fa-eye-slash'); icon.classList.add('fa-eye'); }
    }
});
</script>
</body>
</html>
<?php $this->endPage() ?>
