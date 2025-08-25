<?php

namespace Tests\Unit;

use Tests\TestCase;

class SimpleTest extends TestCase
{
    /** @test */
    public function basic_test()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function can_use_math()
    {
        $result = 2 + 2;
        $this->assertEquals(4, $result);
    }

    /** @test */
    public function can_use_strings()
    {
        $text = "Hello World";
        $this->assertStringContainsString("Hello", $text);
    }
}
