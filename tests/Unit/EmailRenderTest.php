<?php

namespace Tests\Unit;

use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Testing\TestCase;

class EmailRenderTest extends TestCase
{
    public function test_mail_render(): void
    {
        $view = view('mail', ['text' => 'test']);
        // Test fails if render fails e.g. due to undefined variable
        $view->render();
        $this->assertTrue($view instanceof View);
    }
}
