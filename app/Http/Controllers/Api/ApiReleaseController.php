<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Release;
use Illuminate\Http\Request;
use App\Services\CreateRelease;
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
        return $this->respond(Release::orderBy('id', 'desc')->get());
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
        return $this->respond($release);
    }
}
