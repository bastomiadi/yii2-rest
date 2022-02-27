<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\web\Controller;

/**
 * Default controller for the `v1` module
 */
class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';
}
