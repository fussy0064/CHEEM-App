<?php
use yii\helpers\Html;

/** @var \app\models\ServiceRequest[] $pending */
/** @var \app\models\ServiceRequest[] $inProgress */
/** @var \app\models\ServiceRequest[] $resolved */

$this->title = 'Manage Requests';

function renderCard($r) {
    $html = '<div class="card mb-2"><div class="card-body p-2">';
    $html .= '<strong>' . Html::encode(ucfirst($r->category)) . '</strong><br>';
    $html .= Html::encode($r->location) . '<br>';
    $html .= '<span class="badge badge-' . $r->urgency . '">' . $r->getUrgencyLabel() . '</span> ';
    $html .= '<span class="small text-muted">' . Html::encode($r->reference_number) . '</span><br>';
    if ($r->hasLocation()) {
        $html .= '<a href="' . \yii\helpers\Url::to(['request/view-location', 'id' => $r->id]) . '" class="small">📍 View Route &amp; Location</a>';
    }
    $html .= '<div class="mt-2">';
    if ($r->status !== 'in_progress') {
        $html .= Html::beginForm(['request/update-status', 'id' => $r->id], 'post', ['style' => 'display:inline']);
        $html .= Html::hiddenInput('status', 'in_progress');
        $html .= Html::submitButton('Start', ['class' => 'btn btn-sm btn-warning']);
        $html .= Html::endForm();
    }
    if ($r->status !== 'resolved') {
        $html .= Html::beginForm(['request/update-status', 'id' => $r->id], 'post', ['style' => 'display:inline']);
        $html .= Html::hiddenInput('status', 'resolved');
        $html .= Html::submitButton('Resolve', ['class' => 'btn btn-sm btn-success']);
        $html .= Html::endForm();
    }
    $html .= '</div></div></div>';
    return $html;
}
?>
<h2>Kanban Board</h2>

<div class="row">
    <div class="col-md-4">
        <h5>Pending (<?= count($pending) ?>)</h5>
        <?php foreach ($pending as $r) echo renderCard($r); ?>
    </div>
    <div class="col-md-4">
        <h5>In Progress (<?= count($inProgress) ?>)</h5>
        <?php foreach ($inProgress as $r) echo renderCard($r); ?>
    </div>
    <div class="col-md-4">
        <h5>Resolved (<?= count($resolved) ?>)</h5>
        <?php foreach ($resolved as $r) echo renderCard($r); ?>
    </div>
</div>
