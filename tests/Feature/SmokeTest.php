<?php
// tests/Feature/SmokeTest.php
namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    public function test_main_pages_load(): void
    {
        // make() keeps the test user in memory so nothing is written to the database
        $user = User::factory()->make();

        foreach (['/', '/charge', '/committee', '/list'] as $url) {
            $this->actingAs($user)->get($url)->assertOk();
        }
    }
}
