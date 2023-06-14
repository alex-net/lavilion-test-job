<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use Yii;

class EntityAdminController extends Controller
{
    public $modelClass;

    public function behaviors()
    {
        return [
            [
                'class' => AccessControl::class,
                'rules' => [
                    ['allow' => true, 'roles' => ['@']],
                ],
            ],
        ];
    }

    /**
     * получить объект модели  для осуществления последующих действий ...
     * @param int $id номер объекта в базе ..
     */
    private function getModel($id)
    {
        $model = $id ? $this->modelClass::getById($id) : new $this->modelClass();
        if (!$model) {
            throw new NotFoundHttpException("Запись отсуствует");
        }
        return $model;
    }

    /**
     * Страница списка объеектов ..
     */
    public function actionList()
    {
        return $this->render('list');
    }

    /**
     * Редактирование одного объекта ..
     */
    public function actionEdit($id = null)
    {
        $model = $this->getModel($id);

        if ($this->request->isPost) {
            $post = $this->request->post();
            switch (true) {
                case isset($post['save']):
                    if ($model->save($post)) {
                        Yii::$app->session->addFlash('success', 'Данные записи сохранены');
                        return $this->redirect(['list']);
                    }
                    break;
                case isset($post['kill']):
                    if ($model->kill()) {
                        Yii::$app->session->addFlash('info', 'Запись удалена');
                        return $this->redirect(['list']);
                    }
                    break;
            }
        }
        return $this->render('edit', ['model' => $model]);
    }

    /**
     * уделение объекта ..
     * @param int $id номер объекта в базе ..
     */
    public function actionKill($id)
    {
        $model = $this->getModel($id);
        if ($model->kill()) {
            Yii::$app->session->addFlash('info', 'Запись удалена');
        }
        return $this->redirect(['list']);
    }

}