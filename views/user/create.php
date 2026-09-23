<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use app\models\User;

/** @var \app\models\UserForm $model */
$this->title = 'Add User';
?>
<h2>Add User</h2>

<div class="row">
    <div class="col-md-6">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username') ?>
        <?= $form->field($model, 'email') ?>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
                <?= Html::activePasswordInput($model, 'password', ['class' => 'form-control', 'id' => 'newuser-password']) ?>
                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="newuser-password"><i class="fas fa-eye"></i></button>
            </div>
            <?= Html::error($model, 'password', ['class' => 'invalid-feedback d-block']) ?>
        </div>

        <?= $form->field($model, 'role')->dropDownList([
            User::ROLE_FIELD_WORKER => 'Field Worker / Site Manager',
            User::ROLE_HEALTH_OFFICER => 'Health Officer',
            User::ROLE_ADMIN => 'Admin (Superadmin)',
        ], ['prompt' => 'Select role']) ?>

        <div class="d-grid">
            <?= Html::submitButton('Create User', ['class' => 'btn btn-primary btn-lg']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

