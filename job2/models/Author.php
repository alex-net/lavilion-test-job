<?php

namespace app\models;

use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;
use yii\db\Query;
use yii\db\Expression;
use Yii;

class Author extends EntityModel
{
    const TBL = 'authors';
    const NAME_LEN = 30;


    /**
     * Список книг для админки ...
     */
    public static function getList()
    {
        $tbl = static::TBL;
        return new SqlDataProvider([
            'sql' => "select a.*, count(b.id) as bco from $tbl a left join books b on b.author_id = a.id group by a.id",
            'sort' => [
                'attributes' => ['name', 'bco', 'id'],
                'defaultOrder' => ['name' => SORT_ASC],
            ],
        ]);
    }

    /**
     * Списк авторов для Select в форме книги ..
     *
     * @return     <type>  For select.
     */
    public static function getForSelect()
    {
        $tbl = TBL;
        $res = Yii::$app->db->createCommand("select id, name from $tbl order by name asc")->queryAll();
        return ArrayHelper::map($res, 'id', 'name');
    }

    /**
     * список авторов с книгами для фронта сайта ...
     */
    public static function listForFront()
    {
        $q = new Query();
        $q->from(['a' => 'authors']);
        $q->leftJoin(['b' => 'books'],'b.author_id = a.id');
        $q->select([
            'a.name',
            'books' => new Expression("string_agg(b.name, ', ')"),
        ]);
        $q->groupBy('a.id');
        $q->where([
            'and',
            ['=', 'a.active', true],
            [
                'or',
                ['=', 'b.active', true],
                ['is', 'b.id', null],
            ]
        ]);
        $cmd = $q->createCommand();

        return new SqlDataProvider([
            'sql' => $cmd->sql,
            'params' => $cmd->params,
        ]);
    }
}