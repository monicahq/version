<?php

namespace Tests\Unit\Api\Release;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiCreateReleaseTest extends TestCase
{
    use RefreshDatabase;

    protected $jsonRelease = [
        'id',
        'version',
        'released_on',
        'notes',
        'created_at',
        'updated_at',
    ];

    /** @test */
    public function it_creates_a_release()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['create']
        );

        $request = [
            'version' => '1',
            'released_on' => '2021-01-01',
            'notes' => 'notes',
        ];

        $response = $this->json('POST', '/api/releases', $request);

        $response->assertStatus(201);
        $response->assertJsonStructure(['data' => $this->jsonRelease]);

        $releaseId = $response->json('data.id');

        $this->assertDatabaseHas('releases', [
            'id' => $releaseId,
            'version' => '1',
            'released_on' => '2021-01-01 00:00:00',
            'notes' => 'notes',
        ]);
    }

    /** @test */
    public function it_fails_if_version_not_present(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['create']
        );

        $request = [
            'released_on' => '2021-01-01',
            'notes' => 'notes',
        ];

        $response = $this->json('POST', '/api/releases', $request);

        $response->assertStatus(422);

        $response->assertJsonStructure(['error' => ['message', 'error_code']]);

        $this->assertEquals(32, $response->json('error.error_code'));
        $this->assertEquals('The version field is required.', $response->json('error.message.0'));
    }

    /** @test */
    public function it_fails_if_released_on_not_present(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['create']
        );

        $request = [
            'version' => '1',
            'notes' => 'notes',
        ];

        $response = $this->json('POST', '/api/releases', $request);

        $response->assertStatus(422);

        $response->assertJsonStructure(['error' => ['message', 'error_code']]);

        $this->assertEquals(32, $response->json('error.error_code'));
        $this->assertEquals('The released on field is required.', $response->json('error.message.0'));
    }

    /** @test */
    public function it_fails_if_notes_not_present(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['create']
        );

        $request = [
            'version' => '1',
            'released_on' => '2021-01-01',
        ];

        $response = $this->json('POST', '/api/releases', $request);

        $response->assertStatus(422);

        $response->assertJsonStructure(['error' => ['message', 'error_code']]);

        $this->assertEquals(32, $response->json('error.error_code'));
        $this->assertEquals('The notes field is required.', $response->json('error.message.0'));
    }
}
