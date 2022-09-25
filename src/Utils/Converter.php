<?php

namespace Taskforce\Utils;

use \SplFileObject;
use \RuntimeException;


class Converter {
    private object $inputFile;
    private string $outputPath = 'data/queries.sql';

    public function convertData(string $path, string $table)
    {
        $data = [];

        $this->inputFile = new SplFileObject($path);
        $this->inputFile->setFlags(SplFileObject::SKIP_EMPTY);

        foreach ($this->generateLines() as $line) {
            if ($line) {
                $data[] = '(' . $line . ')';
            }
        }

        $fields = array_shift($data);
        $data = implode(',', $data);
        $sql = "INSERT INTO $table $fields VALUES $data;";

        $outputFile = fopen($this->outputPath, 'w');
        $outputFileObject = new SplFileObject($this->outputPath, 'w');
        $outputFileObject->fwrite($sql);
        fclose($outputFile);
    }

    protected function generateLines()
    {
        while (!$this->inputFile->eof()) {
            yield $this->inputFile->fgets();
        }
    }
}

