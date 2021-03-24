<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Release;
use Illuminate\Http\Request;
use App\Services\Release\CreateRelease;
use App\Services\Release\UpdateRelease;
use App\Services\Release\DestroyRelease;
use App\Traits\JsonRespondController;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class ApiReleaseController extends Controller
{
    use JsonRespondController;

    /**
     * Get all releases.
     *
     * @return \Illuminate\Http\JsonResponse|true
     */
    public function index()
    {
        $releases = Release::orderBy('id', 'desc')->get();

        return $this->respond([
            'data' => $releases,
            'count' => $releases->count()
        ]);
    }

    /**
     * Create a new release.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|true
     */
    public function store(Request $request)
    {
        try {
            $release = app(CreateRelease::class)->execute(
                $request->only([
                    'version',
                    'notes',
                    'released_on',
                ])
            );
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }

        $this->setHTTPStatusCode(201);
        return $this->respond([
            'data' => $release
        ]);
    }

    /**
     * Update the given release.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $releaseId
     * @return \Illuminate\Http\JsonResponse|true
     */
    public function update(Request $request, $releaseId)
    {
        try {
            $release = app(UpdateRelease::class)->execute(
                $request->only([
                    'version',
                    'notes',
                    'released_on',
                ]) + [
                    'release_id' => $releaseId
                ]
            );
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }

        return $this->respond([
            'data' => $release
        ]);
    }

    /**
     * Delete the given release.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $releaseId
     * @return \Illuminate\Http\JsonResponse|true
     */
    public function destroy(Request $request, $releaseId)
    {
        try {
            app(DestroyRelease::class)->execute([
                'release_id' => $releaseId
            ]);
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }

        return $this->respondObjectDeleted($releaseId);
    }
}
