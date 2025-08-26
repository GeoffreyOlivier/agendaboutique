<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SimpleTest extends TestCase
{
    #[Test]
    public function basic_test()
    {
        $this->assertTrue(true);
    }

    #[Test]
    public function can_use_math()
    {
        $result = 2 + 2;
        $this->assertEquals(4, $result);
    }

    #[Test]
    public function can_use_strings()
    {
        $text = "Hello World";
        $this->assertStringContainsString("Hello", $text);
    }
}
