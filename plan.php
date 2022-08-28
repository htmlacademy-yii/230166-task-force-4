<?php

class DBHelper
{
    private $link;
    private $lastError = null;

    public function __construct($login, $password, $host, $db)
    {
        $this->link = mysqli_connect($host, $login, $password, $db);

        if (!$this->link) {
            $this->lastError = mysqli_connect_error();
        }
    }

    /**
     * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
     *
     * @param $link mysqli Ресурс соединения
     * @param $sql string SQL запрос с плейсхолдерами вместо значений
     * @param array $data Данные для вставки на место плейсхолдеров
     *
     * @return mysqli_stmt Подготовленное выражение
    */
    public function db_get_prepare_stmt($sql, $data = []) {
        $stmt = mysqli_prepare($this->link, $sql);

        if ($stmt === false) {
            $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
            die($errorMsg);
        }

        if ($data) {
            $types = '';
            $stmt_data = [];

            foreach ($data as $value) {
                $type = 's';

                if (is_int($value)) {
                    $type = 'i';
                }
                else if (is_string($value)) {
                    $type = 's';
                }
                else if (is_double($value)) {
                    $type = 'd';
                }

                if ($type) {
                    $types .= $type;
                    $stmt_data[] = $value;
                }
            }

            $values = array_merge([$stmt, $types], $stmt_data);

            $func = 'mysqli_stmt_bind_param';
            $func(...$values);

            if (mysqli_errno($link) > 0) {
                $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
                die($errorMsg);
            }
        }

        return $stmt;
    }

    /**
     * метод отдает последнюю ошибку
     *
     * @return ?string
     */
    public function getLastError():?string {
        return $this->last_error;
    }

    /**
     * метод отдает последний id
     *
     * @return ?string
     */
    public function getLastId() {
        return mysqli_insert_id($this->link);
    }
}

class Auth
{
    /**
     * Регистрация нового пользователя, применяется класс DataBase
     */
    public function register(array $data) {}

    /**
     * Вход пользователя, применяется класс DataBase
     */
    public function login(array $data) {}

    /**
     * проверка пароля
     */
    public function checkPassword(string $passwordFieldValue, string $currentUserPassword)
    {
        //password_verify($passwordField, $currentUserPassword);
    }

    /**
     * Редактирование пользователя
     */
    public function edit(array $data) {}

    /**
     * Выход пользователя
     */
    public function logout()
    {
        // $_SESSION = [];
        // header("Location: index.php");
    }

    /**
     * Проверка есть ли текущий пользователь
     */
    public function isCurrentUser() {};
}

class User
{

    public function __construct()
    {

    }

    /**
     * Метод возвращает true, если текущий пользователь является заказчиком
    */
    public function isCustomer():boolean {}

    /**
     * Добавление новой задачи
    */
    public function addTask(array $data) {}
}


// class FileManager
//     upload
//     delete

// class Response

// class Location

// class Category

// class Feedback
