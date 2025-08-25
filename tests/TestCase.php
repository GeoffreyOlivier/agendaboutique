<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Configuration par défaut pour les tests
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Configuration des tests - à personnaliser selon les besoins
    }
}
