<?php

namespace App\Http\Controllers\Api;

use App\Models\Release;
use App\Services\Ping\CreatePing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\JsonRespondController;
use Illuminate\Database\QueryException;
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
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }

        // check which version is the current one
        $currentVersion = Release::orderBy('id', 'desc')->first();

        // check which version the user has
        $userVersion = Release::where('version', $request->input('version'))->first();
        $userVersionId = (!$userVersion) ? 0: $userVersion->id;

        // is the version of the user, the current version?
        $isNewVersion = $currentVersion->version !== $request->input('version');

        // how many versions have there been since the version of the user?
        $numberOfVersionsSinceUserVersion = $currentVersion->id - $userVersionId;

        // get all the release notes that have been released since the version of the user
        $releaseNotesMessage = null;
        if ($userVersionId !== $currentVersion->id) {
            $releaseNotesMessage = Release::whereBetween('id', [$userVersionId + 1, $currentVersion->id])
                ->orderBy('id', 'desc')
                ->get()
                ->map(function ($releaseNote) {
                    return '<h2>v'.$releaseNote->version.'</h2>'.'<div class="note">'.$releaseNote->notes.'</div>';
                })
                ->implode('');
        }

        $json = [
            'new_version' => $isNewVersion,
            'latest_version' => $currentVersion->version,
            'number_of_versions_since_user_version' => $numberOfVersionsSinceUserVersion,
            'notes' => $releaseNotesMessage
        ];

        return $this->respond($json);
    }
}
