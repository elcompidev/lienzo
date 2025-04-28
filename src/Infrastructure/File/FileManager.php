<?php

namespace App\Infrastructure\File;

class FileManager
{
    public function readLines(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new \RuntimeException("Input file not found: $filePath");
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return array_filter($lines, fn($line) => trim($line) !== '');
    }

    public function write(string $filePath, string $content): void
    {
        file_put_contents($filePath, $content);
    }

    public function writeMultiple(string $filePath, array $contents): void
    {
        $content = implode("\n\n", $contents);
        $this->write($filePath, $content);
    }
}