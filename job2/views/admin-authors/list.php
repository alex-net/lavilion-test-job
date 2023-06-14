<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;

use app\models\Author;

$this->title = 'Авторы';

echo Html::a('Новый автор', ['edit'], ['class' => 'btn btn-primary']);

echo GridView::widget([
    'dataProvider' => Author::getList(),
    'columns' => [
        'id',
        'name:text:Наименование',
        'bco:integer:Число книг',
        'active:boolean:Доступно',
        [
            'class' => ActionColumn::class,
            'header' => 'Действия',
            'template' => '{update} {delete}',
            'urlCreator' => function($act, $m) {
                $acts = [
                    'update' => 'edit',
                    'delete' => 'kill',
                ];
                return [$acts[$act], 'id' => $m['id']];
            }
        ],
    ],
]);
?>