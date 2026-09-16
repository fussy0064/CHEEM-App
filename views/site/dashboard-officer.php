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
    <div class="col-md-4">
        <div class="card text-center p-3">
            <h3><?= $openCount ?></h3>
            <p class="text-muted mb-0">Open Requests</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-3">
            <h3><?= $inProgressCount ?></h3>
            <p class="text-muted mb-0">In Progress</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-3">
            <h3><?= $resolvedCount ?></h3>
            <p class="text-muted mb-0">Resolved</p>
        </div>
    </div>
</div>

<div class="text-center">
    <?= Html::a('Open Kanban Board', ['request/manage'], ['class' => 'btn btn-primary btn-lg me-2']) ?>
    <?= Html::a('+ Add Service', ['admin/service-create'], ['class' => 'btn btn-outline-primary btn-lg me-2']) ?>
    <?= Html::a('+ Add News', ['admin/news-create'], ['class' => 'btn btn-outline-primary btn-lg']) ?>
</div>
