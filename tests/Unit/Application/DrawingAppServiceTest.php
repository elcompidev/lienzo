<?php

namespace App\Tests\Unit\Application;

use App\Application\Service\DrawingAppService;
use App\Domain\Exception\InvalidCommandException;
use App\Infrastructure\File\FileManager;
use PHPUnit\Framework\TestCase;

class DrawingAppServiceTest extends TestCase
{
    private DrawingAppService $service;

    protected function setUp(): void
    {
        $this->service = new DrawingAppService($this->createMock(FileManager::class));
    }

    public function testHorizontalVerticalLines(): void
    {
        $results = $this->service->executeCommands([
            'C 20 4',
            'L 1 2 6 2', // Horizontal
            'L 6 3 6 4'  // Vertical
        ]);
        
        $lastOutput = end($results);
        $this->assertStringContainsString('|xxxxxx              |', $lastOutput);
        $this->assertStringContainsString('|     x              |', $lastOutput);
    }

    // ... otros métodos de prueba
}