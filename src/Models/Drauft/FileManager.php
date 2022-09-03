<?php

class FileManager
{
    /**
     * Добавление файла
    */
    public function upload() {}

    /**
     * Удаление файла
    */
    public function remove() {}

    /**
     * получение файла по id
    */
    public function getFileById(int $fileId) {}

    /**
     * получение файлов для определенной задачи
    */
    public function getTaskFiles(int $taskId) {}

    /**
     * получение всех файлов пользователя
    */
    public function getUserFiles(int $userId) {}
}
