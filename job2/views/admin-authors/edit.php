<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$this->title = $model ? 'Редактирование автора' : 'Новый автор';
$this->params['breadcrumbs'][] = ['label' => 'Все авторы', 'url' => ['list']];

$f = ActiveForm::begin();

echo $f->field($model, 'name');
echo $f->field($model, 'active')->checkbox();
echo Html::submitButton('Сохранить', ['name' => 'save', 'class' => 'btn btn-primary']);
if ($model->id) {
    echo Html::submitButton('Удалить', ['name' => 'kill', 'class' => 'btn btn-danger float-end']);
}
ActiveForm::end();