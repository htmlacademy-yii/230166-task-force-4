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
     * метод отдает последнюю ошибку
     *
     * @return ?string
     */
    public function getLastError():?string
    {
        return $this->lastError;
    }

    /**
     * метод отдает последний id
     *
     * @return ?string
     */
    public function getLastId() {
        return mysqli_insert_id($this->link);
    }

    /**
     * метод получает все данные из таблицы
     *
     * @param string $table - название таблицы
     *
     * @return ?string
     */
    public function getAll(string $table):?array
    {
        $table = mysqli_real_escape_string($this->link, $table);

        $sql = "SELECT * FROM $table";
        $result = mysqli_query($this->link, $sql);

        if ($result) {
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }

        print('getAll ' . mysqli_error($this->link));
    }

    /**
     * метод получает одну запись по id
     *
     * @param string $table - название таблицы
     * @param string $id - id записи
     *
     * @return ?array
     */
    public function getById(string $table, int $id):?array
    {
        $table = mysqli_real_escape_string($this->link, $table);
        $id = mysqli_real_escape_string($this->link, $id);

        $sql = "SELECT * FROM $table WHERE id = $id";
        $result = mysqli_query($this->link, $sql);

        if ($result) {
            return mysqli_fetch_assoc($result);
        }

        print('getById ' . mysqli_error($this->link));
    }

    /**
     * метод получает одну запись по полю
     *
     * @param string $table - название таблицы
     * @param string $id - id записи
     *
     * @return ?array
     */
    public function getByEmail(string $table, string $email):?array
    {
        $table = mysqli_real_escape_string($this->link, $table);
        $email = mysqli_real_escape_string($this->link, $email);

        $sql = "SELECT * FROM $table WHERE email = $email";
        $result = mysqli_query($this->link, $sql);

        if ($result) {
            return mysqli_fetch_assoc($result);
        }

        print('getById ' . mysqli_error($this->link));
    }

     /**
     * метод добавляет запись в таблицу
     *
     * @param string $table - название таблицы
     * @param array $data - данные
     *
     * @return void
     */
    public function add(string $table, array $data):void
    {
        $table = mysqli_real_escape_string($this->link, $table);

        $keys = array_keys($data);
        $stringOfKeys = implode(', ', $keys);
        $placeholders = array_map(function($value) {
            return $value = '?';
        }, $keys);
        $stringOfPlaceholders = implode(', ', $placeholders);
        $values = array_values($data);

        $sql = "INSERT INTO $table ($stringOfKeys) VALUES ($stringOfPlaceholders)";
        $stmt = $this->getPrepareStmt($sql, $values);
        $result = mysqli_stmt_execute($stmt);

        if (!$result) {
            print('add ' . mysqli_error($this->link));
        }
    }

     /**
     * метод обновляет запись в таблице
     *
     * @param string $table - название таблицы
     * @param array $data - данные которые надо обновить
     * @param int $id - id записи
     *
     * @return void
     */
    public function updateById(string $table, array $data, int $id):void
    {
        $table = mysqli_real_escape_string($this->link, $table);

        $fields = '';

        foreach($data as $key) {
            $fields .= "$key = ?, ";
        }

        $fields = rtrim($fields, ', ');

        $values = array_values($data);

        $sql = "UPDATE $table SET $fields WHERE id = $id";
        $stmt = $this->getPrepareStmt($sql, $values);
        $result = mysqli_stmt_execute($stmt);

        if (!$result) {
            print('updateById ' . mysqli_error($this->link));
        }
    }

    /**
     * метод удаляет запись в таблице
     *
     * @param string $table - название таблицы
     * @param int $id - id записи
     *
     * @return void
     */
    public function deleteById(string $table, int $id):void
    {
        $table = mysqli_real_escape_string($this->link, $table);
        $id = mysqli_real_escape_string($this->link, $id);

        $sql = "DELETE FROM $table WHERE id = $id";
        $result = mysqli_query($this->link, $sql);

        if (!$result) {
            print('deleteById ' . mysqli_error($this->link));
        }
    }

    /**
     * ПРоверяет id
     *
     * @param string $table
     * @param int $id - id записи
     *
     * @return bool
     */
    public function checkId(string $table, int $id):bool
    {
        $table = mysqli_real_escape_string($this->link, $table);
        $id = mysqli_real_escape_string($this->link, $id);

        $sql = "SELECT id FROM $table WHERE id = $id";
        $result = mysqli_query($this->link, $sql);

        if ($result) {
            return (bool) mysqli_fetch_assoc($result);
        }

        print('checkId ' . mysqli_error($this->link));
    }

    /**
     * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
     *
     * @param $sql string SQL запрос с плейсхолдерами вместо значений
     * @param array $data Данные для вставки на место плейсхолдеров
     *
     * @return mysqli_stmt Подготовленное выражение
     */
    private function getPrepareStmt(string $sql, array $data = []):mysqli_stmt
    {
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

            if (mysqli_errno($this->link) > 0) {
                $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
                die($errorMsg);
            }
        }

        return $stmt;
    }
}
