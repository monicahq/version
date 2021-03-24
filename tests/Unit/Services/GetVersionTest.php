<?php

namespace Tests\Unit\Services\Release;

use App\Models\Release;
use App\Services\GetVersion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetVersionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_gets_current_version()
    {
        Release::factory()->create([
            'version' => '1.0.0',
        ]);

        $data = (new GetVersion)->execute([
            'version' => '1.0.0',
        ]);

        $this->assertEquals([
            'new_version' => false,
            'latest_version' => '1.0.0',
            'number_of_versions_since_user_version' => 0,
            'notes' => null,
        ], $data);
    }

    /** @test */
    public function it_gets_data_with_lower_version()
    {
        Release::factory()->create([
            'version' => '1.0.0',
        ]);
        Release::factory()->create([
            'version' => '2.0.0',
            'notes' => 'the notes',
        ]);

        $data = (new GetVersion)->execute([
            'version' => '1.0.0',
        ]);

        $this->assertEquals([
            'new_version' => true,
            'latest_version' => '2.0.0',
            'number_of_versions_since_user_version' => 1,
            'notes' => '<h2>v2.0.0</h2><div class="note">the notes</div>',
        ], $data);
    }

    /** @test */
    public function it_gets_data_with_lower_versions()
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

        $data = (new GetVersion)->execute([
            'version' => '1.0.0',
        ]);

        $this->assertEquals([
            'new_version' => true,
            'latest_version' => '2.1.0',
            'number_of_versions_since_user_version' => 2,
            'notes' => '<h2>v2.1.0</h2><div class="note">the incredible notes</div><h2>v2.0.0</h2><div class="note">the notes</div>',
        ], $data);
    }
}
