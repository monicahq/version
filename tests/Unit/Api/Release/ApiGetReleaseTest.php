<?php

namespace Tests\Unit\Api\Release;

use App\Models\Release;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiGetReleaseTest extends TestCase
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
    public function it_gets_a_release()
    {
        $release = Release::factory()->create();

        Sanctum::actingAs(
            User::factory()->create(),
            ['create']
        );

        $response = $this->json('GET', '/api/releases');

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => ['*' => $this->jsonRelease],
            'count',
        ]);

        $response->assertJsonFragment([
            'id' => $release->id,
        ]);
    }

    /** @test */
    public function it_gets_multiple_releases()
    {
        $releases = Release::factory()->count(10)->create();

        Sanctum::actingAs(
            User::factory()->create(),
            ['create']
        );

        $response = $this->json('GET', '/api/releases');

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => ['*' => $this->jsonRelease],
            'count',
        ]);

        $response->assertJsonFragment([
            'id' => $releases[0]->id,
        ]);
        $response->assertJsonFragment([
            'count' => 10,
        ]);
    }
}
