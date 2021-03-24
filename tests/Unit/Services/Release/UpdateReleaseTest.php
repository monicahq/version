<?php

namespace Tests\Unit\Services\Release;

use App\Models\Release;
use App\Services\Release\UpdateRelease;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UpdateReleaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_updates_a_release()
    {
        $release = Release::factory()->create();

        $request = [
            'release_id' => $release->id,
            'version' => '1',
            'released_on' => '2021-01-01',
            'notes' => 'notes',
        ];

        $new_release = (new UpdateRelease)->execute($request);

        $this->assertDatabaseHas('releases', [
            'id' => $new_release->id,
            'version' => '1',
            'released_on' => '2021-01-01 00:00:00',
            'notes' => 'notes',
        ]);
    }

    /** @test */
    public function it_fails_if_release_id_not_present(): void
    {
        $request = [
            'version' => '1',
            'released_on' => '2021-01-01',
            'notes' => 'notes',
        ];

        $this->expectException(ValidationException::class);

        try {
            (new UpdateRelease)->execute($request);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('release_id', $e->validator->errors()->messages());
            throw $e;
        }
    }

    /** @test */
    public function it_fails_if_release_does_not_exist(): void
    {
        $request = [
            'release_id' => -1,
            'version' => '1',
            'released_on' => '2021-01-01',
            'notes' => 'notes',
        ];

        $this->expectException(ValidationException::class);

        try {
            (new UpdateRelease)->execute($request);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('release_id', $e->validator->errors()->messages());
            throw $e;
        }
    }

    /** @test */
    public function it_fails_if_version_not_present(): void
    {
        $release = Release::factory()->create();

        $request = [
            'release_id' => $release->id,
            'released_on' => '2021-01-01',
            'notes' => 'notes',
        ];

        $this->expectException(ValidationException::class);

        try {
            (new UpdateRelease)->execute($request);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('version', $e->validator->errors()->messages());
            throw $e;
        }
    }

    /** @test */
    public function it_fails_if_released_on_not_present(): void
    {
        $release = Release::factory()->create();

        $request = [
            'release_id' => $release->id,
            'version' => '1',
            'notes' => 'notes',
        ];

        $this->expectException(ValidationException::class);

        try {
            (new UpdateRelease)->execute($request);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('released_on', $e->validator->errors()->messages());
            throw $e;
        }
    }

    /** @test */
    public function it_fails_if_notes_not_present(): void
    {
        $release = Release::factory()->create();

        $request = [
            'release_id' => $release->id,
            'version' => '1',
            'released_on' => '2021-01-01',
        ];

        $this->expectException(ValidationException::class);

        try {
            (new UpdateRelease)->execute($request);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('notes', $e->validator->errors()->messages());
            throw $e;
        }
    }
}
