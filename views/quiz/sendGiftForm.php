<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Доставка!';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('sendGiftFormSubmitted')): ?>

        <div class="alert alert-success">
            Ваш приз отправлен в службу доставку.
        </div>
    <?php elseif (Yii::$app->session->hasFlash('errorSendGift')):?>
        <div class="alert alert-error">
            Произошла ошибка. Попробуйте заново.
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin(['id' => 'sendGift-form']); ?>

                <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>
                <?= $form->field($model, 'country'); ?>
                <?= $form->field($model, 'state'); ?>
                <?= $form->field($model, 'city'); ?>
                <?= $form->field($model, 'zip'); ?>
                <?= $form->field($model, 'address'); ?>

                <div class="form-group">
                    <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'sendGift-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    <?php endif; ?>

</div>
