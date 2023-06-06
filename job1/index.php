<?php
include 'code.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Задание 1</title>
    <style type="text/css">
        .group {margin: 0.7em 0;}
        .errors {color: red; }
        .titl {color: #3f51b5;}
    </style>
</head>
<body>
    <?php if($app->errors): ?>
        <ul class="errors">
            <?php foreach ($app->errors as $err):?>
                <li><?= $err ?></li>
            <?php endforeach?>
        </ul>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf" value="<?= $app->csrf ?>">
        <div class="group">
            <label for="name">Введите имя</label>
            <input type="text" name="name" id="name" value="<?= $app->name ?>" />
        </div>
        <div class="group">
            <label for="family">Введите фамилию</label>
            <input type="text" name="family" id="family" value="<?= $app->family ?>" />
        </div>
        <div class="group avatar">
            <label for="avatar">Загрузите аватарку:</label><br/>
            <input type="file" name="avatar" id="avatar" accept='image/*'>
        </div>
        <?php if ($app->avatar): ?>
        <div class="group kill-avatar">
            <label><input name="kill-avatar" type="checkbox">Удалить аватарку</label>
        </div>
        <?php endif;?>
        <input type="submit" name="save">
        <input type="reset" name="reser">
    </form>
<hr/>
    <div>
        <div class="titl">Результат</div>
        <?= $app->name ?> <?= $app->family ?>
        <div class="avatar-place">
            <?php if ($app->avatar): ?>
                <img src="<?= $app->avatar ?>" />
            <?php else: ?>
                Аватар не загружен
            <?php endif; ?>
        </div>
    </div>
</body>
</html>


