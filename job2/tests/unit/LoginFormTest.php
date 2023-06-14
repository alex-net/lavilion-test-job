<?php

use PHPUnit\Framework\TestCase;

use app\models\LoginForm;


class LoginFormTest extends TestCase
{
    public static function dataSponsor()
    {
        return [
            ['sad', '44234', false],
            ['sad2', '4423422', false],
            ['admin', '123', true],
            ['', '123', false],
            ['3243432', '', false],
        ];
    }

    /**
     * @dataProvider dataSponsor
     */
    public function testForm(string $username, string $password, bool $res)
    {
        $lf = new LoginForm(['username' => $username, 'password' => $password]);

        $this->assertEquals($lf->validate(), $res);
    }

    public function testEnter()
    {
        $lf = new LoginForm(['username' => 'admin', 'password' => '123']);
        $this->assertTrue(Yii::$app->user->isGuest);
        $lf->login();
        $this->assertFalse(Yii::$app->user->isGuest);
        Yii::$app->user->logout();
        $this->assertTrue(Yii::$app->user->isGuest);
    }
}