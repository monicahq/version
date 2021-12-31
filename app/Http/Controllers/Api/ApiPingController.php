<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GetVersion;
use App\Services\Ping\CreatePing;
use App\Traits\JsonRespondController;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ApiPingController extends Controller
{
    use JsonRespondController;

    public function __construct()
    {
        $this->middleware(['guest', 'throttle:api']);
    }

    public function ping(Request $request)
    {
        try {
            app(CreatePing::class)->execute(
                $request->only([
                    'uuid',
                    'version',
                    'contacts',
                ])
            );
            $data = app(GetVersion::class)->execute(
                $request->only([
                    'uuid',
                    'version',
                    'contacts',
                ])
            );
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }

        return $this->respond($data);
    }
}
