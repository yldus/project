<?php


namespace app\controllers;

use app\models\sendGift;
use app\models\Prize;
use yii;
use yii\web\Controller;

class QuizController extends Controller
{

    private $bonusToМoneyRate = 10;

    public function convertMoneyToBonus($money_sum)
    {
        $bonus_sum = $money_sum * $this->bonusToМoneyRate;
        return $bonus_sum;
    }

    public function actionPlay()
    {
        if (yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->view->title = 'Розыгрыш приза!';
        return $this->render('play');
    }

    public function actionPrize()
    {

        if (yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $prize = null;
        $h = 0;
        $sum = 0;
// получить все доступные призы
        $prizes = Prize::find()->where(['>', 'count', 0])->all();

        if (!sizeof($prizes)) {
            Yii::$app->session->setFlash('errorPrizes');
            return $this->redirect(['quiz/prize']);
        }

        // выбрать случайный приз
        $prize_num = rand(0, sizeof($prizes) - 1);
        $prize = $prizes[$prize_num];

        // сохранить тип приза
        $type_prize = $prize->type;
        // сохранить количество призов такого типа
        $cnt_prize = $prize->count;
        // сумма бонусов
        $sum = 0;

        switch ($type_prize) {
            case 1: // деньги,

            case 2: // бонусы
                // получить случайную сумму бонусов в интервале
                $sum = rand(0, $cnt_prize);
                break;
            case 3: // вещь
                break;
            default:
                break;
        }

        $session = yii::$app->session;
        $session->set('prizeId', $prize->id);
        $session->set('prizeSum', $sum);

        return $this->render('prize', ['prize' => $prize, 'sum' => $sum]);
    }

    // получить бонусы
    public function actionBonus()
    {
        if (yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $session = yii::$app->session;

        if (!$session->has('prizeId') or !$session->has('prizeSum')) {
            Yii::$app->session->setFlash('errorBonus');
            return $this->redirect(['quiz/play']);
        }

        $sum = $session->get('prizeSum');
        $session->remove('prizeId');
        $session->remove('prizeSum');

        return $this->render('bonus', ['sum' => $sum]);
    }

    // получить приз
    public function actionSendGift()
    {
        $this->view->title = 'Отправка подарка';
        $model = new SendGift();

        if ($model->load(Yii::$app->request->post())) {

            $session = yii::$app->session;

            if (!$session->has('prizeId')) {
                Yii::$app->session->setFlash('errorSendGift');
                return $this->redirect(['quiz/play']);
            }

            //$prize_id = Yii::$app->request->get('id');
            $prize_id = $session->get('prizeId');
            $prize_count = 1;

            $model->user_id = Yii::$app->user->id;
            $model->prize_id = Yii::$app->request->get('id');
            $model->status = 0; // не отправлен

            if ($model->save()) {
                Prize::reduceCountPrize($prize_id, $prize_count);
                Yii::$app->session->setFlash('sendGiftFormSubmitted');
                $session->remove('prizeId');
                $session->remove('prizeSum');
                return $this->redirect(['quiz/send-gift']);
            }
        }

        return $this->render('sendGiftForm', [
            'model' => $model,
        ]);
    }

    //
    public function actionConvertToBonus()
    {
        $session = yii::$app->session;

        if (!$session->has('prizeId') or !$session->has('prizeSum')) {
            Yii::$app->session->setFlash('errorBonus');
            return $this->redirect(['quiz/play']);
        }

        $sum = $session->get('prizeSum');

        $bonus_sum = $this->convertMoneyToBonus($sum);
        $session->set('prizeSum', $bonus_sum);

        return $this->redirect(['quiz/bonus']);
    }
}