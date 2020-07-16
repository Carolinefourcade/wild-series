<?php


namespace App\Service;


class Slugify
{
    public function generate(string $input): string
    {
        $inputClean = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $input);
        $inputClean = preg_replace('/\s+/', '-', $inputClean);
        $inputClean = mb_strtolower($inputClean);
        $inputClean = trim($inputClean);
        $inputClean = preg_replace('/[^A-Za-z0-9 ]/', '', $inputClean);

        return $inputClean;
    }
}
