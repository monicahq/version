<?php

namespace App\Http\Controllers\Api;

use App\Models\Ping;
use App\Models\Host;
use App\Models\Release;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\JsonRespondController;

class ApiPingController extends Controller
{
    use JsonRespondController;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function ping(Request $request)
    {
        $data = $request->json()->all();

        // check if we have all the params that we need
        if (array_key_exists('uuid', $data) == false) {
            return $this->respondInvalidParameters('uuid not present');
        }

        // check if we have all the params that we need
        if (array_key_exists('version', $data) == false) {
            return $this->respondInvalidParameters('version not present');
        }

        // check if we have all the params that we need
        if (array_key_exists('contacts', $data) == false) {
            return $this->respondInvalidParameters('contacts not present');
        }

        // record host
        $host = Host::where('uuid', $data['uuid'])->first();
        if (is_null($host)) {
            $host = new Host;
            $host->uuid = $data['uuid'];
            $host->save();
        }

        // record ping
        $ping = new Ping;
        $ping->host_id = $host->id;
        $ping->uuid = $data['uuid'];
        $ping->version = $data['version'];
        $ping->number_of_contacts = $data['contacts'];
        $ping->save();

        // check which version is the current one
        $currentVersion = Release::orderBy('id', 'desc')->first();

        // check which version the user has
        $userVersion = Release::where('version', $data['version'])->first();
        if (!$userVersion) {
            $userVersionId = 0;
        } else {
            $userVersionId = $userVersion->id;
        }

        // is the version of the user, the current version?
        $isNewVersion = ($currentVersion->version == $data['version'] ? false : true);

        // how many versions have there been since the version of the user?
        $numberOfVersionsSinceUserVersion = $currentVersion->id - $userVersionId;

        // get all the release notes that have been released since the version of the user
        $releaseNotesMessage = null;
        if (! ($userVersionId == $currentVersion->id)) {
            $releaseNotes = Release::whereBetween('id', [$userVersionId + 1, $currentVersion->id])->orderBy('id', 'desc')->get();
            foreach ($releaseNotes as $releaseNote) {
                $releaseNotesMessage .= '<h2>v'.$releaseNote->version.'</h2>'.'<div class="note">'.$releaseNote->notes.'</div>';
            }
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
