<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<h1><?= Html::encode($this->title) ?></h1>
<?php if (Yii::$app->session->hasFlash('okTransferToCardOn')): ?>

    <div class="alert alert-success">
        Деньги отправлены на счет.
    </div>
<?php elseif (Yii::$app->session->hasFlash('errorTransferToCardOn')):?>
    <div class="alert alert-error">
        Произошла ошибка. Попробуйте заново.
    </div>
<?php else: ?>
    <?php $form = ActiveForm::begin(['options' => ['id' => 'transerToCardForm']]); ?>
    <label>Сумма</label>: <?= $model->sum; ?>

    <?= $form->field($model, 'holder'); ?>
    <?= $form->field($model, 'type')->radioList($model->getCardType()); ?>
    <?= $form->field($model, 'number'); ?>
    <?= HTML::submitButton('Отправить', ['class' => 'btn btn-success'])  ?>
    <?php ActiveForm::end(); ?>
<?php endif; ?>