<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var \app\models\NewsPost $model */
$this->title = $model->isNewRecord ? 'Add News Post' : 'Edit News Post';
?>
<h2><?= $this->title ?></h2>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= $form->field($model, 'title') ?>
<?= $form->field($model, 'content')->textarea(['rows' => 4]) ?>
<?= $form->field($model, 'imageFile')->fileInput() ?>
<?php if ($model->image_path): ?>
    <img src="<?= Html::encode(Yii::getAlias('@web/' . $model->image_path)) ?>" style="max-height:120px" class="mb-3 d-block">
<?php endif; ?>
<?= $form->field($model, 'active')->checkbox() ?>

<?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>
