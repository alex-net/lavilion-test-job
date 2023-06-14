<?php

namespace app\controllers;

use yii\rest\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\filters\auth\HttpBearerAuth;

use app\models\Book;

class BooksRestApiController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => HttpBearerAuth::class,
                'only' => ['update', 'kill'],
            ]
        ];
    }

    public function beforeAction($act)
    {
        if (!parent::beforeAction($act)) {
            return false;
        }
        $this->response->format = Response::FORMAT_JSON;
        return true;
    }

    /**
     * книжки списком
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function actionList()
    {
        return Book::getList();
    }

    /**
     * Просмотр одной книги
     *
     * @param      int  $id     Номер книги в базе ..
     */
    public function actionView($id)
    {
        return $this->getModel($id);
    }

    /**
     * получить объект модели по номеру ...
     *
     * @param      int                          $id     Номер книги в базе ...
     */
    private function getModel($id)
    {
        $model = Book::getById($id);
        if (!$model) {
            throw new NotFoundHttpException('Запись отсутствует');
        }
        return $model;
    }

    /**
     * обновление книги по post запросу ..
     *
     * @param      int  $id     Номер книги в базе ...
     *
     * @return     array   ( description_of_the_return_value )
     */
    public function actionUpdate($id)
    {
        $model = $this->getModel($id);

        $model->attributes = $this->request->post();
        if ($model->save()) {
            return $model;
        }
        $this->response->statusCode = 500;
        return ['errors' => $model->errors];
    }

    /**
     * Удаление книги ....
     *
     * @param      int  $id     Номер книги в базе ...
     */
    public function actionKill($id)
    {
        $model = $this->getModel($id);
        $model->kill();
        return [
            'ok' => true,
            'id' => $id,
        ];
    }
}