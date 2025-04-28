<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class DrawingToolTest extends KernelTestCase
{
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        self::bootKernel();
        $application = new Application();
        
        $command = self::$kernel->getContainer()->get('App\Presentation\Command\DrawingCommand');
        $application->add($command);
        
        $this->commandTester = new CommandTester($application->find('app:draw'));
    }

    public function testLinesWithinBounds(): void
    {
        $this->commandTester->setInputs(['C 20 4', 'L 1 2 6 2', 'L 6 3 6 4', 'Q']);
        $this->commandTester->execute(['command' => 'app:draw']);
        
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('|xxxxxx              |', $output);
        $this->assertStringContainsString('|     x              |', $output);
    }

    // ... otros métodos de prueba
}