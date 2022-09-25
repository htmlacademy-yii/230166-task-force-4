<?php

namespace Taskforce\Utils;

use \SplFileObject;
use \RuntimeException;

use TaskForce\Exceptions\ExceptionWrongParameter;
use TaskForce\Exceptions\ExceptionFailedToOpenFile;

class Converter {
    private object $inputFile;
    private string $outputPath = 'data/insert.sql';

    public function convertData(string $path, string $table): void
    {
        if (!file_exists($path)) {
            throw new ExceptionWrongParameter($path, 'convertData');
        }

        try {
            $this->inputFile = new SplFileObject($path);
        }
        catch (RuntimeException $exception) {
            throw new ExceptionFailedToOpenFile($path);
        }

        $query = $this->createQuery($table);
        $this->writeQuery($query);
    }

    /**
     * generateLines генерируем строки из файла
     *
     * @return object
     */
    protected function generateLines(): object
    {
        while (!$this->inputFile->eof()) {
            yield $this->inputFile->fgetcsv();
        }
    }

    /**
     * getData получаем массив из генератора
     *
     * @return array
     */
    protected function getData(): array
    {
        $data = [];

        foreach ($this->generateLines() as $line) {
            if ($this->validateLine($line)) {
                $data[] = $line;
            }
        }

        return $data;
    }

    /**
     * createQuery создаем запрос для добавления данных в таблицу
     *
     * @param  string $table название таблицы
     * @return string
     */
    protected function createQuery(string $table): string
    {
        $data = $this->getData();
        $headerData = implode(',', array_shift($data));

        foreach ($data as $line) {
            $values[] = '(\'' . implode('\', \'', $line) . '\')';
        }

        $values = implode(',', $values);

        return 'INSERT INTO ' . $table . ' (' . preg_replace('/\s+|[[:^print:]]/', '', $headerData) .') VALUES ' . $values . ';';
    }

    /**
     * writeQuery создаем файл, добавляем в него запрос
     *
     * @param  string $query
     * @return void
     */
    protected function writeQuery(string $query): void
    {
        $outputFile = fopen($this->outputPath, 'w');
        fwrite($outputFile, $query);
        fclose($outputFile);
    }

    /**
     * validateLine возвращает что строка это не пустой массив
     * и что каждое значение массива это строка
     *
     * @param  array $line
     * @return bool
     */
    protected function validateLine(array $line): bool
    {
        if (!count($line)) {
            return false;
        }

        foreach ($line as $value) {
            if (!is_string($value)) {
                return false;
            }
        }

        return true;
    }
}

