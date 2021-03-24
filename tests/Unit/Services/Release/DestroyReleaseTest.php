<?php

namespace Tests\Unit\Services\Release;

use App\Models\Release;
use App\Services\Release\DestroyRelease;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class DestroyReleaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_destroys_a_release()
    {
        $release = Release::factory()->create();

        $request = [
            'release_id' => $release->id,
        ];

        (new DestroyRelease)->execute($request);

        $this->assertDatabaseMissing('releases', [
            'id' => $release->id,
        ]);
    }

    /** @test */
    public function it_fails_if_release_id_not_present(): void
    {
        $this->expectException(ValidationException::class);

        try {
            (new DestroyRelease)->execute([]);
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
        ];

        $this->expectException(ValidationException::class);

        try {
            (new DestroyRelease)->execute($request);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('release_id', $e->validator->errors()->messages());
            throw $e;
        }
    }
}
