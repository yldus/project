<?php if (Yii::$app->session->hasFlash('errorBonus')): ?>
    <div class="alert alert-error">
        Произошла ошибка. Попробуйте заново.
    </div>
<?php else: ?>
    <div class="alert alert-success">Бонусные баллы <?= $sum ?> начислены!</div>
<?php endif ?>
