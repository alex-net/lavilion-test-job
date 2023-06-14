<?php

namespace app\models;

use yii\data\SqlDataProvider;

class Book extends EntityModel
{
    const TBL = 'books';
    const NAME_LEN = 60;

    public $author_id;

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['author_id', 'integer'],
            ['author_id', 'required'],
            ['author_id', 'checkInDb'],
        ]);
    }

    /**
     * проверка сущетсоввания автора в базе ...
     */
    public function checkInDb($attr)
    {
        $a = Author::getById($this->$attr);
        if (!$a) {
            $this->addError($attr, 'Автор не найден');
        }
    }

    public function attributeLabels()
    {
        $al = parent::attributeLabels();
        $al['author_id'] = 'Автор книги';
        return $al;
    }

    public function fields()
    {
        return [
            'id', 'name', 'active',
            'author' => function() {
                $author = Author::getById($this->author_id);
                if ($author) {
                    return $author->name;
                }
            }
        ];
    }

    /**
     * список книг для админки ..
     */
    public static function getList()
    {
        $tbl = static::TBL;
        return new SqlDataProvider([
            'sql' => "select b.*, a.name as aname from $tbl b left join authors a on a.id = b.author_id",
            'sort' => [
                'attributes' => ['name', 'aname', 'id'],
                'defaultOrder' => ['name' => SORT_ASC],
            ],
        ]);
    }

}