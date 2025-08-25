<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ValidationService;
use App\Services\CacheService;

class ArchitectureTest extends TestCase
{
    /** @test */
    public function validation_service_can_be_instantiated()
    {
        $service = new ValidationService();
        $this->assertInstanceOf(ValidationService::class, $service);
    }

    /** @test */
    public function cache_service_can_be_instantiated()
    {
        $service = new CacheService();
        $this->assertInstanceOf(CacheService::class, $service);
    }

    /** @test */
    public function validation_service_has_expected_methods()
    {
        $service = new ValidationService();
        
        $this->assertTrue(method_exists($service, 'validateSocialUrls'));
        $this->assertTrue(method_exists($service, 'validateGeographicData'));
        $this->assertTrue(method_exists($service, 'validateContactInfo'));
        $this->assertTrue(method_exists($service, 'validateFinancialInfo'));
    }

    /** @test */
    public function cache_service_has_expected_methods()
    {
        $service = new CacheService();
        
        $this->assertTrue(method_exists($service, 'remember'));
        $this->assertTrue(method_exists($service, 'get'));
        $this->assertTrue(method_exists($service, 'put'));
        $this->assertTrue(method_exists($service, 'forget'));
    }

    /** @test */
    public function can_validate_social_urls()
    {
        $service = new ValidationService();
        
        $data = [
            'site_web' => 'https://example.com',
            'instagram_url' => 'https://instagram.com/user',
        ];
        
        $result = $service->validateSocialUrls($data);
        
        $this->assertEquals($data, $result);
    }

    /** @test */
    public function can_validate_geographic_info()
    {
        $service = new ValidationService();
        
        $data = [
            'ville' => 'Paris',
            'code_postal' => '75000',
            'pays' => 'France',
        ];
        
        $result = $service->validateGeographicData($data);
        
        $this->assertEquals($data, $result);
    }

    /** @test */
    public function can_use_cache_service()
    {
        $service = new CacheService();
        
        // Test de base
        $service->put('test_key', 'test_value', 60);
        $value = $service->get('test_key');
        
        $this->assertEquals('test_value', $value);
        
        // Nettoyer
        $service->forget('test_key');
    }
}
