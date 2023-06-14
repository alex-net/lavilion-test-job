<?php 

namespace app\models;

use yii\base\Model;
use yii\db\Query;
use Yii;

abstract class EntityModel extends Model
{
    const TBL = '';
    const NAME_LEN = 10;

    public $id, $name, $active;

    public function rules()
    {
        return [
            ['active', 'boolean'],
            ['name', 'trim'],
            ['name', 'string', 'max' => static::NAME_LEN],
            ['name', 'required'],
            ['name', 'existInDb']
        ];
    }

    public function existInDb($attr)
    {
        $where = ['and', ['name' => $this->name]];
        if ($this instanceof Book) {
            $where[1]['author_id'] = $this->author_id;
        }
        if ($this->id) {
            $where[] = ['not', ['=', 'id', $this->id]];
        }
        $q = new Query();
        $q->from(static::TBL);
        $q->where($where);
        if ($q->exists()) {
            $this->addError($attr, 'Запись с таким названием уже присутствует в базе ');
        }
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Наименование',
            'active' => 'Активность',
        ];
    }

    abstract public static function getList();


    /**
     * запрос по Id ...
     *
     * @param      int  $id     Номер сузности в базе
     */
    public static function getById($id)
    {
        $q = new Query();
        $q->from(static::TBL)->where(['id' => $id])->limit(1);
        $res = $q->one();
        if ($res) {
            return new static($res);
        }
    }

    /**
     * Сохранение сущности в базе ...
     */
    public function save($data = [])
    {
        if ($data && !$this->load($data) || !$this->validate()) {
            return false;
        }

        $attrs = $this->getAttributes($this->activeAttributes());

        if ($this->id) {
            Yii::$app->db->createCommand()->update(static::TBL, $attrs, ['id' => $this->id])->execute();
        } else {
            Yii::$app->db->createCommand()->insert(static::TBL, $attrs)->execute();
            $this->id = Yii::$app->db->lastInsertID;
        }
        return true;
    }

    public function kill()
    {
        if ($this->id) {
            Yii::$app->db->createCommand()->delete(static::TBL, ['id' => $this->id])->execute();
            return true;
        }
        return false;
    }

}