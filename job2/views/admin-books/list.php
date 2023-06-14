<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;

use app\models\Book;

$this->title = 'Книги';

echo Html::a('Новая книга', ['edit'], ['class' => 'btn btn-primary']);

echo GridView::widget([
    'dataProvider' => Book::getList(),
    'columns' => [
        'id',
        'name:text:Наименование',
        'aname:text:Автор',
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