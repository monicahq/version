<?php

namespace Tests\Unit\Services\Release;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Release\CreateRelease;
use Illuminate\Validation\ValidationException;

class CreateReleaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_release()
    {
        $request = [
            'version' => '1',
            'released_on' => '2021-01-01',
            'notes' => 'notes'
        ];

        $release = (new CreateRelease)->execute($request);

        $this->assertDatabaseHas('releases',[
            'id' => $release->id,
            'version' => '1',
            'released_on' => '2021-01-01 00:00:00',
            'notes' => 'notes'
        ]);
    }

    /** @test */
    public function it_fails_if_version_not_present(): void
    {
        $request = [
            'released_on' => '2021-01-01',
            'notes' => 'notes'
        ];

        $this->expectException(ValidationException::class);

        try {
            (new CreateRelease)->execute($request);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('version', $e->validator->errors()->messages());
            throw $e;
        }
    }

    /** @test */
    public function it_fails_if_released_on_not_present(): void
    {
        $request = [
            'version' => '1',
            'notes' => 'notes'
        ];

        $this->expectException(ValidationException::class);

        try {
            (new CreateRelease)->execute($request);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('released_on', $e->validator->errors()->messages());
            throw $e;
        }
    }

    /** @test */
    public function it_fails_if_notes_not_present(): void
    {
        $request = [
            'version' => '1',
            'released_on' => '2021-01-01',
        ];

        $this->expectException(ValidationException::class);

        try {
            (new CreateRelease)->execute($request);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('notes', $e->validator->errors()->messages());
            throw $e;
        }
    }
}
