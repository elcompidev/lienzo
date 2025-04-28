<?php

namespace App\Domain\Repository;

use App\Domain\Model\Canvas;

interface CanvasRepositoryInterface
{
    public function render(Canvas $canvas): string;
}