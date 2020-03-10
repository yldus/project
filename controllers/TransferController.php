<?php


namespace app\controllers;

use yii;
use app\models\Prize;
use app\models\TransferToCard;
use yii\web\Controller;

class TransferController extends Controller {
    public function actionTransferToCard() {

        if (Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }

        $model = new TransferToCard();

        if ($model->load(Yii::$app->request->post())) {
            $session = yii::$app->session;

            if (!$session->has('prizeId') or !$session->has('prizeSum')) {
                Yii::$app->session->setFlash('errorTransferToCardOn');
                return $this->redirect(['quiz/play']);
            }

            $prize_id = $session->get('prizeId');
            $sum = $session->get('prizeSum');

            $model->sum = $sum;
            $model->type = 1;

            $model->user_id = Yii::$app->user->id;
            $model->status = 0; // не отправлен

            if ($model->save()) {
                Prize::reduceCountPrize($prize_id, $sum);
                Yii::$app->session->setFlash('okTransferToCardOn');
                $session->remove('prizeId');
                $session->remove('prizeSum');
                return $this->redirect(['transfer/transfer-to-card']);
            }
        }

        $this->view->title = 'Получить деньги на карту';

        return $this->render('transferToCardForm', compact('model'));

    }

}