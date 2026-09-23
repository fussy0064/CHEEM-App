<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var \app\models\ResetPasswordForm $model */
/** @var \app\models\User $targetUser */

$this->title = 'Reset Password';
?>
<h2>Reset Password for <?= Html::encode($targetUser->username) ?></h2>

<?php $form = ActiveForm::begin(); ?>

<div class="mb-3">
    <label class="form-label">New Password</label>
    <div class="input-group">
        <?= Html::activePasswordInput($model, 'password', ['class' => 'form-control', 'id' => 'reset-password-1', 'placeholder' => 'Min 8 characters']) ?>
        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="reset-password-1"><i class="fas fa-eye"></i></button>
    </div>
    <?= Html::error($model, 'password', ['class' => 'invalid-feedback d-block']) ?>
</div>

<div class="mb-3">
    <label class="form-label">Repeat New Password</label>
    <div class="input-group">
        <?= Html::activePasswordInput($model, 'password_repeat', ['class' => 'form-control', 'id' => 'reset-password-2', 'placeholder' => 'Repeat new password']) ?>
        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="reset-password-2"><i class="fas fa-eye"></i></button>
    </div>
    <?= Html::error($model, 'password_repeat', ['class' => 'invalid-feedback d-block']) ?>
</div>

<?= Html::submitButton('Reset Password', ['class' => 'btn btn-warning']) ?>
<?= Html::a('Cancel', ['index'], ['class' => 'btn btn-outline-secondary']) ?>

<?php ActiveForm::end(); ?>
