<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

use app\models\Author;

$this->title = $model ? 'Редактирование Книги' : 'Новая книга';
$this->params['breadcrumbs'][] = ['label' => 'Все книги', 'url' => ['list']];

$f = ActiveForm::begin();

echo $f->field($model, 'name');
echo $f->field($model, 'active')->checkbox();
echo $f->field($model, 'author_id')->dropdownList(Author::getForSelect(), ['prompt' => '- не выбран -']);
echo Html::submitButton('Сохранить', ['name' => 'save', 'class' => 'btn btn-primary']);
if ($model->id) {
    echo Html::submitButton('Удалить', ['name' => 'kill', 'class' => 'btn btn-danger float-end']);
}
ActiveForm::end();