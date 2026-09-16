<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ServiceRequest;

/** @var ServiceRequest $model */

$this->title = 'New Request';
?>
<h2>Report / Request a Service</h2>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= $form->field($model, 'category')->dropDownList([
    'water' => 'Water & Sanitation',
    'waste' => 'Waste Management',
    'pest' => 'Pest Control',
    'safety' => 'Health & Safety Hazard',
    'risk' => 'Risk / Scenario Assessment',
    'economic' => 'Economic Assessment',
], ['prompt' => 'Select category']) ?>

<?= $form->field($model, 'location')->textInput(['placeholder' => 'Site / area name']) ?>

<?= $form->field($model, 'urgency')->dropDownList([
    'low' => 'Low', 'medium' => 'Medium', 'high' => 'High',
]) ?>

<?= $form->field($model, 'description')->textarea(['rows' => 4, 'placeholder' => 'Describe the issue']) ?>

<?= $form->field($model, 'photoFile')->fileInput() ?>

<div class="d-grid">
    <?= Html::submitButton('Submit Request', ['class' => 'btn btn-danger btn-lg']) ?>
</div>

<?php ActiveForm::end(); ?>
