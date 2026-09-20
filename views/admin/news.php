<?php
use yii\helpers\Html;

/** @var \app\models\NewsPost[] $posts */
$this->title = 'Manage News';
?>
<h2>Manage Home Page News</h2>
<?= Html::a('+ Add News Post', ['news-create'], ['class' => 'btn btn-primary mb-3']) ?>

<div class="table-responsive">
<table class="table table-bordered bg-white">
    <thead><tr><th>Image</th><th>Title</th><th>Active</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($posts as $n): ?>
        <tr>
            <td><?php if ($n->image_path): ?><img src="<?= Html::encode(Yii::getAlias('@web/' . $n->image_path)) ?>" style="height:40px"><?php endif; ?></td>
            <td><?= Html::encode($n->title) ?></td>
            <td><?= $n->active ? 'Yes' : 'No' ?></td>
            <td>
                <?= Html::a('Edit', ['news-update', 'id' => $n->id], ['class' => 'btn btn-sm btn-outline-secondary']) ?>
                <?= Html::a('Delete', ['news-delete', 'id' => $n->id], [
                    'class' => 'btn btn-sm btn-outline-danger',
                    'data' => ['confirm' => 'Delete this post?', 'method' => 'post'],
                ]) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
