<?php

namespace App\Application\Service;

use App\Domain\Model\Canvas;
use App\Domain\Exception\InvalidCommandException;
use App\Infrastructure\File\FileManager;

class DrawingAppService
{
    private FileManager $fileManager;
    private ?Canvas $canvas = null;

    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    public function executeCommands(array $commands): array
    {
        $results = [];
        
        foreach ($commands as $command) {
            $command = trim($command);
            if (empty($command)) continue;
            
            try {
                $result = $this->executeCommand($command);
                if ($result !== null) {
                    $results[] = $result;
                }
            } catch (\InvalidArgumentException $e) {
                throw new InvalidCommandException($e->getMessage());
            }
        }
        
        return $results;
    }

    private function executeCommand(string $command): ?string
    {
        $parts = preg_split('/\s+/', $command);
        $cmd = strtoupper($parts[0] ?? '');

        switch ($cmd) {
            case 'C':
                return $this->createCanvas($parts);
            case 'L':
                $this->drawLine($parts);
                return $this->canvas?->render();
            case 'R':
                $this->drawRectangle($parts);
                return $this->canvas?->render();
            case 'B':
                $this->bucketFill($parts);
                return $this->canvas?->render();
            default:
                throw new InvalidCommandException("Unknown command: {$cmd}");
        }
    }

    private function createCanvas(array $parts): string
    {
        if (count($parts) < 3) {
            throw new \InvalidArgumentException('Canvas command requires width and height');
        }

        $width = (int)$parts[1];
        $height = (int)$parts[2];
        $this->canvas = new Canvas($width, $height);
        return $this->canvas->render();
    }

    private function drawLine(array $parts): void
    {
        if ($this->canvas === null) {
            throw new \InvalidArgumentException("Canvas not created");
        }

        if (count($parts) < 5) {
            throw new \InvalidArgumentException('Line command requires x1,y1,x2,y2');
        }

        $x1 = (int)$parts[1];
        $y1 = (int)$parts[2];
        $x2 = (int)$parts[3];
        $y2 = (int)$parts[4];
        $this->canvas->drawLine($x1, $y1, $x2, $y2);
    }

    private function drawRectangle(array $parts): void
    {
        if ($this->canvas === null) {
            throw new \InvalidArgumentException("Canvas not created");
        }

        if (count($parts) < 5) {
            throw new \InvalidArgumentException('Rectangle command requires x1,y1,x2,y2');
        }

        $x1 = (int)$parts[1];
        $y1 = (int)$parts[2];
        $x2 = (int)$parts[3];
        $y2 = (int)$parts[4];
        $this->canvas->drawRectangle($x1, $y1, $x2, $y2);
    }

    private function bucketFill(array $parts): void
    {
        if ($this->canvas === null) {
            throw new \InvalidArgumentException("Canvas not created");
        }

        if (count($parts) < 4) {
            throw new \InvalidArgumentException('Bucket fill command requires x,y,color');
        }

        $x = (int)$parts[1];
        $y = (int)$parts[2];
        $color = $parts[3];
        $this->canvas->bucketFill($x, $y, $color);
    }
}