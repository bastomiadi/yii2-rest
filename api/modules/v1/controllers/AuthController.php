<?php

namespace api\modules\v1\controllers;

use common\models\LoginForm;
use common\models\User;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use yii\rest\ActiveController;
use yii\rest\Controller;

/**
 * Default controller for the `v1` module
 */
class AuthController extends Controller
{
    //public $modelClass = 'common\models\User';
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerAuth::class,
            'optional' => [
                'login',
            ],
        ];

        return $behaviors;
    }

    // untuk verbs yg diperlukan misal get/put/patch/dsb
    protected function verbs()
    {
        return [
            'login' => ['POST'],
            'data' => ['POST'],
        ];
    }

    //fungsi untuk login api
    public function actionLogin(){
        $model = new LoginForm();
        if ($model->load(Yii::$app->getRequest()->getBodyParams(), '') && $model->login()) {
            $response = [
                'isSuccess' => 200,
                'message' => 'login berhasil!',
                'id' => Yii::$app->user->identity->id,
                'username' => Yii::$app->user->identity->username,
                'email' => (!empty(Yii::$app->user->identity->email)) ? Yii::$app->user->identity->email : '',
                'token' => User::generateToken(Yii::$app->user->identity->id),
            ];
            return $response;
        } else {
            $model->validate();
            return $model;
        }
    }

    //hanya untuk tes token
    public function actionData()
    {
        return $this->asJson([
            'data' => User::find()->all(),
            'success' => true,
        ]);
    }
}
