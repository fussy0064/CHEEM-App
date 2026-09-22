<?php
use yii\helpers\Html;
use app\models\ServiceRequest;

/** @var \app\models\User $user */
$this->title = 'Dashboard';

$openCount = ServiceRequest::find()->where(['status' => 'pending'])->count();
$inProgressCount = ServiceRequest::find()->where(['status' => 'in_progress'])->count();
$resolvedCount = ServiceRequest::find()->where(['status' => 'resolved'])->count();
?>
<h2>Welcome, <?= Html::encode($user->username) ?></h2>

<div class="row my-4">
    <!-- Left sidebar: quick actions -->
    <div class="col-md-3 mb-4">
        <div class="list-group shadow-sm">
            <?= Html::a('📋 Open Kanban Board', ['request/manage'], ['class' => 'list-group-item list-group-item-action fw-bold']) ?>
            <?php if ($user->isAdmin()): ?>
                <?= Html::a('➕ Add Health Officer', ['user/create'], ['class' => 'list-group-item list-group-item-action']) ?>
            <?php endif; ?>
            <?= Html::a('➕ Add Service', ['admin/service-create'], ['class' => 'list-group-item list-group-item-action']) ?>
            <?= Html::a('➕ Add News', ['admin/news-create'], ['class' => 'list-group-item list-group-item-action']) ?>
        </div>
    </div>

    <!-- Right: stats -->
    <div class="col-md-9">
        <div class="row">
            <div class="col-sm-4 mb-3">
                <div class="card text-center p-3">
                    <h3><?= $openCount ?></h3>
                    <p class="text-muted mb-0">Open Requests</p>
                </div>
            </div>
            <div class="col-sm-4 mb-3">
                <div class="card text-center p-3">
                    <h3><?= $inProgressCount ?></h3>
                    <p class="text-muted mb-0">In Progress</p>
                </div>
            </div>
            <div class="col-sm-4 mb-3">
                <div class="card text-center p-3">
                    <h3><?= $resolvedCount ?></h3>
                    <p class="text-muted mb-0">Resolved</p>
                </div>
            </div>
        </div>
    </div>
</div>
