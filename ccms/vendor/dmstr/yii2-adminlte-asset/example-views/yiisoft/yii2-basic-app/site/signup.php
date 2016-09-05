<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = 'Sign up';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="form-box" id="login-box">

    <div class="header"><?= Html::encode($this->title) ?></div>
    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
    <div class="body bg-gray">
        <p>Please fill out the following fields to Sign Up:</p>
        <?= $form->field($model, 'username') ?>
        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'password')->passwordInput() ?>

    </div>
    <div class="footer">

        <?= Html::submitButton('Go', ['class' => 'btn bg-olive btn-block', 'name' => 'login-button']) ?>
        <p><a href="login">Login</a></p>
    </div>
    <?php ActiveForm::end(); ?>
</div>
