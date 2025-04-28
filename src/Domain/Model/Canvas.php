<?php

namespace App\Domain\Model;

class Canvas
{
    private array $grid;
    private int $width;
    private int $height;

    public function __construct(int $width, int $height)
    {
        if ($width < 1 || $height < 1) {
            throw new \InvalidArgumentException('Canvas dimensions must be at least 1x1');
        }

        $this->width = $width;
        $this->height = $height;
        $this->initializeGrid();
    }

    private function initializeGrid(): void
    {
        $this->grid = array_fill(0, $this->height, array_fill(0, $this->width, ' '));
    }

    public function drawLine(int $x1, int $y1, int $x2, int $y2): void
    {
        $x1--; $y1--; $x2--; $y2--; // Convert to 0-based index
        
        $this->validateCoordinates($x1, $y1);
        $this->validateCoordinates($x2, $y2);

        if ($x1 == $x2) { // Vertical line
            for ($y = min($y1, $y2); $y <= max($y1, $y2); $y++) {
                $this->grid[$y][$x1] = 'x';
            }
        } elseif ($y1 == $y2) { // Horizontal line
            for ($x = min($x1, $x2); $x <= max($x1, $x2); $x++) {
                $this->grid[$y1][$x] = 'x';
            }
        } else {
            throw new \InvalidArgumentException('Only horizontal or vertical lines are supported');
        }
    }

    public function drawRectangle(int $x1, int $y1, int $x2, int $y2): void
    {
        $this->drawLine($x1, $y1, $x2, $y1); // Top
        $this->drawLine($x1, $y1, $x1, $y2); // Left
        $this->drawLine($x2, $y1, $x2, $y2); // Right
        $this->drawLine($x1, $y2, $x2, $y2); // Bottom
    }

    public function bucketFill(int $x, int $y, string $color): void
    {
        $x--; $y--; // Convert to 0-based index
        $this->validateCoordinates($x, $y);

        $targetColor = $this->grid[$y][$x] ?? ' ';
        if ($targetColor == $color) {
            return;
        }

        $this->floodFill($x, $y, $targetColor, $color);
    }

    private function floodFill(int $x, int $y, string $targetColor, string $newColor): void
    {
        if ($x < 0 || $x >= $this->width || $y < 0 || $y >= $this->height) {
            return;
        }

        if ($this->grid[$y][$x] != $targetColor) {
            return;
        }

        $this->grid[$y][$x] = $newColor;

        $this->floodFill($x + 1, $y, $targetColor, $newColor);
        $this->floodFill($x - 1, $y, $targetColor, $newColor);
        $this->floodFill($x, $y + 1, $targetColor, $newColor);
        $this->floodFill($x, $y - 1, $targetColor, $newColor);
    }

    private function validateCoordinates(int $x, int $y): void
    {
        if ($x < 0 || $y < 0 || $x >= $this->width || $y >= $this->height) {
            throw new \InvalidArgumentException("Coordinates out of bounds");
        }
    }

    public function render(): string
    {
        $border = str_repeat('-', $this->width + 2);
        $output = [$border];

        foreach ($this->grid as $row) {
            $output[] = '|' . implode('', $row) . '|';
        }

        $output[] = $border;
        return implode("\n", $output);
    }
}