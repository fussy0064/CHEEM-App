<?php
use yii\helpers\Html;

/** @var \app\models\ServiceRequest[] $requests */
$this->title = 'My Requests';
?>
<h2>My Requests</h2>

<div class="table-responsive">
<table class="table table-bordered bg-white">
    <thead>
        <tr><th>Ref</th><th>Category</th><th>Location</th><th>Urgency</th><th>Status</th><th>Map</th></tr>
    </thead>
    <tbody>
    <?php foreach ($requests as $r): ?>
        <tr>
            <td><?= Html::encode($r->reference_number) ?></td>
            <td><?= Html::encode(ucfirst($r->category)) ?></td>
            <td><?= Html::encode($r->location) ?></td>
            <td><span class="badge badge-<?= $r->urgency ?>"><?= $r->getUrgencyLabel() ?></span></td>
            <td><?= $r->getStatusLabel() ?></td>
            <td><?php if ($r->hasLocation()): ?><?= Html::a('📍 View', $r->getMapUrl(), ['target' => '_blank']) ?><?php endif; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
