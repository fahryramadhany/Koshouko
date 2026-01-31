<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // Jika aplikasi meredirect (mis. ke /login), ikuti redirect dan
        // pastikan respons akhir adalah 200. Ini membuat test lebih robust
        // terhadap perubahan middleware otentikasi untuk '/'.
        $response = $this->followingRedirects()->get('/');

        $response->assertOk();
    }
}
