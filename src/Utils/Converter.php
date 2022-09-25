<?php

namespace Taskforce\Utils;

use \SplFileObject;
use \RuntimeException;


class Converter {
    private object $inputFile;
    private string $outputPath = 'data/queries.sql';


    protected function getData()
    {
        foreach ($this->getLines() as $line) {
            $data[] = $line;
        }


        return $data;
    }



    public function convertData(string $path, string $table)
    {
        $data = [];

        $this->inputFile = new SplFileObject($path);

        foreach ($this->generateLines() as $line) {
            $data[] = $line;
        }

        $outputFile = fopen($this->outputPath, 'w');
        $outputFile = new SplFileObject($this->outputPath, 'a');

        $fields = array_shift($data);

        var_dump($data);
        var_dump($fields);

        $sql = "INSERT INTO $table ($fields) VALUES($data);";

        var_dump($sql);

        // $outputFile->fwrite($sql);

        // foreach ($this->getLines() as $line) {
        //     $outputFile->fwrite($line);
        // }

        // fclose($this->outputPath);
    }

    protected function generateLines()
    {
        while (!$this->inputFile->eof()) {
            yield $this->inputFile->fgets();
        }
    }



    // protected function createOutputFile()
    // {
    //     $outputFile = fopen($this->outputPath, 'w');
    //     $outputFile = new SplFileObject($this->outputPath, 'a');
    // }
}

