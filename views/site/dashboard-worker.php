<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ServiceRequest;

/** @var \app\models\User $user */
$this->title = 'Dashboard';

$recent = ServiceRequest::find()->where(['user_id' => $user->id])->orderBy(['created_at' => SORT_DESC])->limit(5)->all();
?>
<h2>Welcome, <?= Html::encode($user->username) ?></h2>

<div class="text-center my-4">
    <?= Html::a('+ Report Issue / Request Service', ['request/create'], ['class' => 'btn btn-lg btn-danger px-5 py-3']) ?>
</div>

<h4 class="mt-4">Your Recent Requests</h4>
<?php if (empty($recent)): ?>
    <p class="text-muted">No requests yet.</p>
<?php else: ?>
    <div class="row">
        <?php foreach ($recent as $r): ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h6><?= Html::encode(ucfirst($r->category)) ?></h6>
                        <p class="mb-1"><?= Html::encode($r->location) ?></p>
                        <span class="badge bg-secondary"><?= $r->getStatusLabel() ?></span>
                        <span class="badge badge-<?= $r->urgency ?>"><?= $r->getUrgencyLabel() ?></span>
                        <p class="small text-muted mt-2 mb-0">Ref: <?= Html::encode($r->reference_number) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<p class="mt-3"><?= Html::a('View all my requests', ['request/my']) ?></p>
