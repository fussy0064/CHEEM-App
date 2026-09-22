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
                style="border-color: rgba(255,255,255,0.5);">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <?php if (!Yii::$app->user->isGuest): $u = Yii::$app->user->identity; ?>
                <ul class="navbar-nav ms-auto align-items-md-center">
                    <?php if ($u->isAdmin()): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= Url::to(['/user/index']) ?>">Users</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= Url::to(['/admin/services']) ?>">Services</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= Url::to(['/admin/news']) ?>">News</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= Url::to(['/suggestion/index']) ?>">Suggestions</a></li>
                    <?php elseif ($u->isHealthOfficer()): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= Url::to(['/admin/services']) ?>">Services</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= Url::to(['/admin/news']) ?>">News</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= Url::to(['/suggestion/index']) ?>">Suggestions</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="<?= Url::to(['/suggestion/create']) ?>">Suggestions</a></li>
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
