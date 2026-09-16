<?php
use yii\helpers\Html;

/** @var \app\models\Service[] $services */
$this->title = 'Manage Services';
?>
<h2>Manage Services</h2>
<?= Html::a('+ Add Service', ['service-create'], ['class' => 'btn btn-primary mb-3']) ?>

<table class="table table-bordered bg-white">
    <thead><tr><th>Name</th><th>Category</th><th>Active</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($services as $s): ?>
        <tr>
            <td><?= Html::encode($s->name) ?></td>
            <td><?= Html::encode(ucfirst($s->category)) ?></td>
            <td><?= $s->active ? 'Yes' : 'No' ?></td>
            <td>
                <?= Html::a('Edit', ['service-update', 'id' => $s->id], ['class' => 'btn btn-sm btn-outline-secondary']) ?>
                <?= Html::a('Delete', ['service-delete', 'id' => $s->id], [
                    'class' => 'btn btn-sm btn-outline-danger',
                    'data' => ['confirm' => 'Delete this service?', 'method' => 'post'],
                ]) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
