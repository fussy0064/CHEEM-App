<?php
use yii\helpers\Html;

/** @var \app\models\Suggestion[] $suggestions */
$this->title = 'Suggestions';
?>
<h2>Client Suggestions</h2>

<table class="table table-bordered bg-white">
    <thead><tr><th>Message</th><th>Status</th><th>Date</th></tr></thead>
    <tbody>
    <?php foreach ($suggestions as $s): ?>
        <tr>
            <td><?= Html::encode($s->message) ?></td>
            <td><?= Html::encode($s->status) ?></td>
            <td><?= Yii::$app->formatter->asDatetime($s->created_at) ?></td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($suggestions)): ?>
        <tr><td colspan="3" class="text-muted">No suggestions yet.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
