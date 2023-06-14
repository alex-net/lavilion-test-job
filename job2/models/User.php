<?php

namespace app\models;

use yii\base\Model;
use yii\web\IdentityInterface;
use yii\db\Query;
use Yii;

class User extends Model implements IdentityInterface
{
    public $id, $login, $mail;
    public $pass_hash, $password;
    public $active, $auth_key, $access_token;

    public function rules()
    {
        return [
            [['password', 'login', 'mail'], 'trim'],
            ['login', 'string', 'max' => 25],
            ['mail', 'string', 'max' => 50],
            ['password', 'string'],
            [['login', 'password', 'mail'], 'required'],
            ['login', 'existInDb'],
            ['active', 'boolean'],
            [['auth_key', 'access_token'], 'default', 'value' => function() {
                return Yii::$app->security->generateRandomString();
            }],
            ['mail', 'email'],
        ];
    }

    public function existInDb($attr)
    {
        $where = ['and', ['login' => $this->$attr]];
        if ($this->id) {
            $where[] = ['not', ['=', 'id', $this->id]];
        }
        $q = new Query();
        $q->from('users');
        $q->where($where);
        if ($q->count()) {
            $this->addError($attr, "Запись с логином {$this->login} уже есть в базе");
        }
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $attrs = $this->getAttributes($this->activeAttributes(), ['password']);
        if ($this->password) {
            $attrs['pass_hash'] = Yii::$app->security->generatePasswordHash($this->password);
        }

        if ($this->id) {
            Yii::$app->db->createCommand()->update('users', $attrs, ['id' => $this->id])->execute();
        } else {
            Yii::$app->db->createCommand()->insert('users', $attrs)->execute();
            $this->id = Yii::$app->db->lastInsertID;
        }
        return true;
    }

    public function getUsername()
    {
        return $this->login;
    }

    private static function findBy($where)
    {
        $q = new Query();
        $q->from('users');
        $q->limit(1);
        $q->where($where);
        $res = $q->one();
        return $res ? new static($res) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findBy(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findBy(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findBy(['login' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->pass_hash);
    }
}
