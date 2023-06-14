<?php

use yii\db\Migration;

use app\models\User;
/**
 * Class m230613_160254_user_table
 */
class m230613_160254_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // создаём таблицу с пользователями
        $this->createTable('users', [
            'id' => $this->primaryKey()->comment('Ключик'),
            'login' => $this->string(25)->notNull()->comment('Логин'),
            'mail' => $this->string(50)->notNull()->comment('Мыло'),
            'active' => $this->boolean()->notNull()->defaultValue(false)->comment('Акивность'),
            'pass_hash' => $this->string(60)->notNull()->comment('Хеш пароля'),
            'auth_key' => $this->string(32)->notNull()->comment('Auth key'),
            'access_token' => $this->string(32)->notNull()->comment('Auth token'),
        ]);
        foreach (['login', 'auth_key', 'access_token', 'active', 'mail'] as $field) {
            $this->createIndex("users-$field-ind", 'users', $field, $field == 'login');
        }


        $u = new User([
            'login' => 'admin',
            'mail' => 'admin@mail.ru',
            'password' => '123',
            'active' => true,
        ]);
        if ($u->save()) {
            echo '!';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('users');
    }
}
