<?php

class Auth
{

    public function __construct (public DBHelper $db)
    {}

    /**
     * Регистрация нового пользователя
     */
    public function register($data) {
        $this->db->add('user', $data);
    }

    /**
     * Вход пользователяl
     */
    public function login(string $email, string $password):bool
     {
        // Проверяем существует ли пользователь
        // Записываем в сессию

        $sql = "SELECT * FROM user WHERE email = $email AND password = $password LIMIT 1";
        $result = mysqli_query($this->db->link, $sql);

        if ($result) {
            $_SESSION['currentUser'] = mysqli_fetch_assoc($result);
            return true;
        }

        print('Такой пользователь не зарегистрирован');
        return false;
    }

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
        unset($_SESSION['currentUser']);
    }

    /**
     * Проверка есть ли текущий пользователь
     */
    public function isCurrentUser() {
        return (bool) $_SESSION['currentUser'];
    }

    public function getCurrentUser()
    {
        if ($this->isCurrentUser()) {
            return $_SESSION['currentUser'];
        }
    }

    // public function getFullName():string
    // {
    //     $user = $this->getCurrentUser();

    //     return $user['firstName'] . $user['secondName'];
    // }

}
