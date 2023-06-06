<?php
/**
 * класс приложения ..
 */
class App
{
    /**
     * расположение файла данных ...
     *
     * @var        string
     */
    const FILE_NAME = './job.data';

    private string $name, $family, $avatar;

    private array $errors = [];

    public function __construct()
    {
        session_start();
        $this->name = $this->family = $this->avatar = '';
        $this->loadFromFile();
        if ($this->loadFromPost() && $this->saveData()) {
            header('Location: ' . $_SERVER['REQUEST_URI']);
        }
    }
    /**
     * загрузка данных из файла
     */
    private function loadFromFile()
    {
        if (file_exists(static::FILE_NAME)) {
            $data = json_decode(file_get_contents(static::FILE_NAME), 1);
            foreach ($data as $key => $val) {
                $this->$key = $val;
            }
        }
    }

    /**
     * Сохранение даннных в файл
     *
     * @return     bool  ( description_of_the_return_value )
     */
    private function saveData()
    {
        if ($this->errors) {
            return false;
        }

        $data = [];
        foreach (['name', 'family', 'avatar'] as $field) {
            $data[$field] = $this->$field;
        }
        $avatar = $this->saveAvatar();
        header('Debug: '.print_r($avatar,1));
        if ($avatar) {
            $data['avatar'] = $avatar;
        }
        file_put_contents(static::FILE_NAME, json_encode($data));
        return true;
    }

    /**
     * Вернуть путьь к каталгу с аватарками .. или пуьб к одной из аватарок
     *
     * @param      string  $tail   имя файла запрашиваемой аватарки ..
     *
     * @return     string  путь к каталоги или файлу ..
     */
    private function getFolderForAvarar($tail = null)
    {
        $folder = $_SERVER['DOCUMENT_ROOT'] .  dirname($_SERVER['DOCUMENT_URI']) . '/avatar';
        if ($tail) {
            $folder .= '/' . $tail;
        }
        return $folder;
    }

    /**
     * Сохранение загруженного через форму ватара ...
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    private function saveAvatar()
    {
        if (!isset($_FILES['avatar']['error']) || $_FILES['avatar']['error'] != UPLOAD_ERR_OK) {
            return;
        }

        $forder = $this->getFolderForAvarar();//$_SERVER['DOCUMENT_ROOT'] . '/avatar';
        if (!file_exists($forder)) {
            mkdir($forder);
        }
        if ($this->avatar && file_exists($forder . '/'. $this->avatar)) {
            unlink($forder . '/'. $this->avatar);
        }

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $forder . '/' .  $_FILES['avatar']['name'])) {
            return $_FILES['avatar']['name'];
        }
    }

    /**
     * Убийство аватара. без загрузки замены...
     */
    private function killAvatar()
    {
        $file = $this->getFolderForAvarar($this->avatar);// $_SERVER['DOCUMENT_ROOT'] . '/avatar/' . ;
        if ($this->avatar && file_exists($file)) {
            unlink($file);
            $this->avatar = '';
        }
    }

    /**
     * заполнение полей класса из массива $_POST
     *
     * @return     bool  true = В случае успешной загрузки
     */
    private function loadFromPost()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return false;
        }

        if (!$this->csrfValidate()) {
            header('HTTP/1.1 400 Not Found');
            return false;
        }

        $post = $_POST;
        foreach (['name', 'family'] as $field) {
            if (empty($post[$field])) {
                $this->errors[] = "Поле $field не заполнено.";
            } else {
                $this->$field = $post[$field];
            }
        }

        if (!empty($post['kill-avatar'])) {
            $this->killAvatar();
            header('Avatar-killer: '.$this->avatar);
        }

        return true;
    }

    /**
     * Валидация post-запроса по полю csrf
     *
     * @return     bool  True = если всё правильно ..
     */
    private function csrfValidate()
    {
        return !empty($_POST['csrf']) && !empty($_SESSION['csrf']) &&  base64_encode($_SESSION['csrf']) == $_POST['csrf'];
    }

    /**
     * получение значений закрытых полей ...
     *
     * @param      string  $name   The name
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function __get(string $name)
    {
        if ($name == 'avatar' && $this->avatar)  {
            return $_SERVER['REQUEST_URI'] . 'avatar/' . $this->avatar;
        }
        if (!empty($this->$name)) {
            return $this->$name;
        }
        if ($name == 'csrf') {
            $_SESSION['csrf'] = random_bytes(15);
            return base64_encode($_SESSION['csrf']);
        }
    }
}

$app = new App();




