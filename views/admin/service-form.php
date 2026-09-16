<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var \app\models\Service $model */
$this->title = $model->isNewRecord ? 'Add Service' : 'Edit Service';
?>
<h2><?= $this->title ?></h2>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'category')->dropDownList([
    'water' => 'Water & Sanitation',
    'waste' => 'Waste Management',
    'pest' => 'Pest Control',
    'safety' => 'Health & Safety',
    'risk' => 'Risk / Scenario Assessment',
    'economic' => 'Economic Assessment',
]) ?>
<?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
<?= $form->field($model, 'active')->checkbox() ?>

<?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>
