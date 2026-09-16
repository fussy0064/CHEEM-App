<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var \app\models\Suggestion $model */
$this->title = 'Suggestions';
?>
<h2>Send a Suggestion</h2>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'message')->textarea(['rows' => 5, 'placeholder' => 'Your feedback or suggestion...']) ?>

<?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>
