<?php

namespace App\Tests\Unit\Domain;

use App\Domain\Model\Canvas;
use PHPUnit\Framework\TestCase;

class CanvasTest extends TestCase
{
    public function testCanvasInitialization(): void
    {
        $canvas = new Canvas(20, 4);
        $expected = <<<EOT
----------------------
|                    |
|                    |
|                    |
|                    |
----------------------
EOT;

        $this->assertEquals($this->normalizeLineEndings($expected), $this->normalizeLineEndings($canvas->render()));
    }

    public function testDrawHorizontalLine(): void
    {
        $canvas = new Canvas(20, 4);
        $canvas->drawLine(1, 2, 6, 2); // Línea horizontal en fila 2
        
        $output = $canvas->render();
        $this->assertStringContainsString('|xxxxxx              |', $output);
    }

    public function testDrawVerticalLine(): void
    {
        $canvas = new Canvas(20, 4);
        $canvas->drawLine(6, 1, 6, 4); // Línea vertical en columna 6
        
        $output = $canvas->render();
        $lines = explode("\n", $output);
        
        $this->assertStringContainsString('|     x              |', $lines[1]);
        $this->assertStringContainsString('|     x              |', $lines[2]);
        $this->assertStringContainsString('|     x              |', $lines[3]);
    }

    public function testBoundaryLines(): void
    {
        $canvas = new Canvas(20, 4);
        
        // Línea horizontal en el borde superior
        $canvas->drawLine(1, 1, 20, 1);
        // Línea horizontal en el borde inferior
        $canvas->drawLine(1, 4, 20, 4);
        // Línea vertical en el borde izquierdo
        $canvas->drawLine(1, 1, 1, 4);
        // Línea vertical en el borde derecho
        $canvas->drawLine(20, 1, 20, 4);
        
        $output = $canvas->render();
        
        $this->assertStringContainsString('|xxxxxxxxxxxxxxxxxxxx|', $output); // Borde superior
        $this->assertStringContainsString('|x                  x|', $output); // Bordes laterales
    }

    public function testInvalidCoordinates(): void
    {
        $canvas = new Canvas(20, 4);
        
        $this->expectException(\InvalidArgumentException::class);
        $canvas->drawLine(0, 0, 21, 5); // Coordenadas claramente fuera de límites
    }

    public function testDiagonalLineNotSupported(): void
    {
        $canvas = new Canvas(20, 4);
        
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Only horizontal or vertical lines are supported');
        $canvas->drawLine(1, 1, 3, 3); // Línea diagonal
    }

    private function normalizeLineEndings(string $text): string
    {
        return str_replace("\r\n", "\n", $text);
    }
}