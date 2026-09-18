<?php
use yii\helpers\Html;

/** @var \app\models\User[] $users */
$this->title = 'Manage Users';
?>
<h2>Manage Users</h2>
<?= Html::a('+ Add Health Officer / User', ['create'], ['class' => 'btn btn-primary mb-3']) ?>

<table class="table table-bordered bg-white">
    <thead><tr><th>Username</th><th>Email</th><th>Phone</th><th>Role</th><th>Status</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($users as $u): ?>
        <tr>
            <td><?= Html::encode($u->username) ?></td>
            <td><?= Html::encode($u->email) ?></td>
            <td><?= Html::encode($u->phone_number ?: '-') ?> <?= $u->phone_verified ? '' : '<span class="badge bg-warning text-dark">unverified</span>' ?></td>
            <td><span class="badge bg-info text-dark"><?= Html::encode(ucfirst(str_replace('_', ' ', $u->role))) ?></span></td>
            <td><?= $u->status ? '<span class="text-success">Active</span>' : '<span class="text-danger">Disabled</span>' ?></td>
            <td>
                <?php if ($u->id !== Yii::$app->user->id): ?>
                    <?= Html::a('Reset Password', ['reset-password', 'id' => $u->id], ['class' => 'btn btn-sm btn-outline-warning']) ?>
                    <?= Html::a($u->status ? 'Disable' : 'Enable', ['toggle-status', 'id' => $u->id], [
                        'class' => 'btn btn-sm btn-outline-secondary',
                        'data' => ['method' => 'post', 'confirm' => 'Are you sure?'],
                    ]) ?>
                    <?= Html::a('Delete', ['delete', 'id' => $u->id], [
                        'class' => 'btn btn-sm btn-outline-danger',
                        'data' => ['method' => 'post', 'confirm' => 'Delete this user permanently?'],
                    ]) ?>
                <?php else: ?>
                    <span class="text-muted small">(you)</span>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
