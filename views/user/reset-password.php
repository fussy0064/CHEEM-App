<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var \app\models\ResetPasswordForm $model */
/** @var \app\models\User $targetUser */

$this->title = 'Reset Password';
?>
<h2>Reset Password for <?= Html::encode($targetUser->username) ?></h2>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'password')->passwordInput(['placeholder' => 'New password (min 8 characters)']) ?>
<?= $form->field($model, 'password_repeat')->passwordInput(['placeholder' => 'Repeat new password']) ?>

<?= Html::submitButton('Reset Password', ['class' => 'btn btn-warning']) ?>
<?= Html::a('Cancel', ['index'], ['class' => 'btn btn-outline-secondary']) ?>

<?php ActiveForm::end(); ?>
