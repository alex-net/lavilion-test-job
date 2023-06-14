<?php

use yii\widgets\ListView;

use app\models\Author;

$this->title = 'Авторы и книги';


echo ListView::widget([
    'dataProvider' => Author::listForFront(),
    'itemView' => 'author-item',
    'summary' => false,
]);
