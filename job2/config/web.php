<?php

use app\controllers\EntityAdminController;
use app\models\Author;
use app\models\Book;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru',
    'name' => 'Lavivion test job',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // 'baseUrl' => 'dsadsad',
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'qU9nZtghx1RB6c-BAPEJoMP0MyopLtRu',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'GET api/v1/books/list' => 'books-rest-api/list',
                'GET api/v1/books/<id:\d+>' => 'books-rest-api/view',
                'POST api/v1/books/<id:\d+>' => 'books-rest-api/update',
                'DELETE api/v1/books/<id:\d+>' => 'books-rest-api/kill',

                'books' => 'site/books',
                'admin/authors/<id:\d+>/kill' => 'admin-authors/kill',
                'admin/authors/<id:\d+>' => 'admin-authors/edit',
                'admin/authors/add' => 'admin-authors/edit',
                'admin/authors' => 'admin-authors/list',

                'admin/books/<id:\d+>/kill' => 'admin-books/kill',
                'admin/books/<id:\d+>' => 'admin-books/edit',
                'admin/books/add' => 'admin-books/edit',
                'admin/books' => 'admin-books/list',
            ],
        ],
        'assetManager' => [
            'linkAssets' => true,
        ],

    ],
    'controllerMap' => [
        'admin-books' => [
            'class' => EntityAdminController::class,
            'modelClass' => Book::class,
        ],
        'admin-authors' => [
            'class' => EntityAdminController::class,
            'modelClass' => Author::class,
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '*', '::1'],
    ];

}

return $config;
