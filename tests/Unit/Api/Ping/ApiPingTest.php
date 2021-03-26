<?php

namespace Tests\Unit\Api\Ping;

use App\Models\Host;
use App\Models\Release;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiPingTest extends TestCase
{
    use RefreshDatabase;

    protected $jsonRelease = [
        'new_version',
        'latest_version',
        'number_of_versions_since_user_version',
        'notes',
    ];

    /** @test */
    public function it_sends_a_ping()
    {
        Release::factory()->create([
            'version' => '1.0.0',
        ]);

        $response = $this->json('POST', '/ping', [
            'uuid' => '1',
            'version' => '1.0.0',
            'contacts' => '10',
        ]);

        $response->assertOk();
        $response->assertJsonStructure($this->jsonRelease);

        $response->assertJsonFragment([
            'new_version' => false,
            'latest_version' => '1.0.0',
            'number_of_versions_since_user_version' => 0,
            'notes' => null,
        ]);

        $this->assertDatabaseHas('hosts', [
            'uuid' => '1',
        ]);
        $host = Host::where(['uuid' => '1'])->first();

        $this->assertDatabaseHas('pings', [
            'host_id' => $host->id,
            'version' => '1.0.0',
            'uuid' => '1',
            'number_of_contacts' => 10,
        ]);
    }

    /** @test */
    public function it_sends_a_ping_for_existing_host()
    {
        Release::factory()->create([
            'version' => '1.0.0',
        ]);
        $host = Host::factory()->create();

        $response = $this->json('POST', '/ping', [
            'uuid' => $host->uuid,
            'version' => '1.0.0',
            'contacts' => '10',
        ]);

        $response->assertOk();
        $response->assertJsonStructure($this->jsonRelease);

        $this->assertDatabaseHas('pings', [
            'host_id' => $host->id,
            'version' => '1.0.0',
            'uuid' => $host->uuid,
            'number_of_contacts' => 10,
        ]);
    }

    /** @test */
    public function it_sends_a_ping_with_lower_version()
    {
        Release::factory()->create([
            'version' => '1.0.0',
        ]);
        Release::factory()->create([
            'version' => '2.0.0',
            'notes' => 'the notes',
        ]);

        $response = $this->json('POST', '/ping', [
            'uuid' => 'a',
            'version' => '1.0.0',
            'contacts' => '10',
        ]);

        $response->assertJsonFragment([
            'new_version' => true,
            'latest_version' => '2.0.0',
            'number_of_versions_since_user_version' => 1,
            'notes' => '<h2>v2.0.0</h2><div class="note">the notes</div>',
        ]);
    }

    /** @test */
    public function it_sends_a_ping_with_lower_versions()
    {
        Release::factory()->create([
            'version' => '1.0.0',
        ]);
        Release::factory()->create([
            'version' => '2.0.0',
            'notes' => 'the notes',
        ]);
        Release::factory()->create([
            'version' => '2.1.0',
            'notes' => 'the incredible notes',
        ]);

        $response = $this->json('POST', '/ping', [
            'uuid' => 'a',
            'version' => '1.0.0',
            'contacts' => '10',
        ]);

        $response->assertJsonFragment([
            'new_version' => true,
            'latest_version' => '2.1.0',
            'number_of_versions_since_user_version' => 2,
            'notes' => '<h2>v2.1.0</h2><div class="note">the incredible notes</div><h2>v2.0.0</h2><div class="note">the notes</div>',
        ]);
    }
}
