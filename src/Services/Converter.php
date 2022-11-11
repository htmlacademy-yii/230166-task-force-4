<?php

namespace Taskforce\Services;

use \SplFileObject;
use \RuntimeException;

use Taskforce\Exceptions\ExceptionWrongParameter;
use Taskforce\Exceptions\ExceptionFailedToOpenFile;

class Converter {
    private SplFileObject $inputFile;
    private string $outputPath = 'data/sql/';

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
        $this->writeQuery($query, $table);
    }

    /**
     * setOutputPath меняет путь до папки с выходными данными
     *
     * @param  string $outputPath
     * @return void
     */
    public function setOutputPath(string $outputPath): void
    {
        $this->outputPath = $outputPath;
    }

    /**
     * generateLines генерируем строки из файла
     *
     * @return object
     */
    protected function generateLines(): \Iterator
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
        $headerData = array_shift($data);
        $headerData = implode(',', $headerData);

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
    protected function writeQuery(string $query, string $table): void
    {
        $outputFile = fopen($this->outputPath . $table . '.sql', 'w');
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

