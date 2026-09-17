<?php
use yii\helpers\Html;

/** @var \app\models\NewsPost[] $news */
/** @var \app\models\Service[] $services */

$this->title = 'Welcome';
?>
<div class="text-center mb-4">
    <img src="<?= \yii\helpers\Url::to('@web/images/logo.png') ?>" alt="CHEEM logo" style="height:100px;width:100px;border-radius:50%;object-fit:cover;margin-bottom:12px">
    <h1>CHEEM Portal</h1>
    <p class="lead">Consultation, Health &amp; Environment Management</p>
    <?= Html::a('Login', ['site/login'], ['class' => 'btn btn-primary me-2']) ?>
    <?= Html::a('Sign Up', ['site/signup'], ['class' => 'btn btn-outline-primary']) ?>
</div>

<?php if (!empty($news)): ?>
<div id="newsSlider" class="carousel slide mb-4" data-bs-ride="carousel">
    <div class="carousel-inner rounded shadow-sm">
        <?php foreach ($news as $i => $n): ?>
            <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                <?php if ($n->image_path): ?>
                    <img src="<?= Html::encode(Yii::getAlias('@web/' . $n->image_path)) ?>" class="d-block w-100" style="max-height:320px;object-fit:cover" alt="">
                <?php endif; ?>
                <div class="carousel-caption bg-dark bg-opacity-50 rounded p-2">
                    <h5><?= Html::encode($n->title) ?></h5>
                    <p class="small mb-0"><?= Html::encode(mb_strimwidth($n->content ?? '', 0, 150, '...')) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if (count($news) > 1): ?>
    <button class="carousel-control-prev" type="button" data-bs-target="#newsSlider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#newsSlider" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
    <?php endif; ?>
</div>
<?php endif; ?>

<h3>Services We Offer</h3>
<div class="row">
    <?php foreach ($services as $s): ?>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5><?= Html::encode($s->name) ?></h5>
                    <span class="badge bg-info text-dark mb-2"><?= Html::encode(ucfirst($s->category)) ?></span>
                    <p class="small"><?= Html::encode($s->description) ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (empty($services)): ?>
        <p class="text-muted">No services listed yet.</p>
    <?php endif; ?>
</div>
