<?php

namespace App\Http\Controllers;

use App\Ping;
use App\Host;
use App\Release;
use Validator;
use Illuminate\Http\Request;

class PingController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function ping(Request $request)
    {
        $data = $request->json()->all();

        // check if we have all the params that we need
        if (array_key_exists('uuid', $data) == false) {
            return response()->json([
                'error' => 'invalid_access_key',
                'code' => '102',
            ], 400);
        }

        // check if we have all the params that we need
        if (array_key_exists('version', $data) == false) {
            return response()->json([
                'error' => 'invalid_access_key',
                'code' => '102',
            ], 400);
        }

        // check if we have all the params that we need
        if (array_key_exists('contacts', $data) == false) {
            return response()->json([
                'error' => 'invalid_access_key',
                'code' => '102',
            ], 400);
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
        $currentVersion = Release::orderBy('released_on', 'asc')->orderBy('version', 'desc')->first();

        // check which version the user has
        $userVersion = Release::where('version', $data['version'])->first();
        if (!$userVersion) {
            $userVersionId = 1;
        } else {
            $userVersionId = $userVersion->id;
        }

        // is the version of the user, the current version?
        $isNewVersion = ($currentVersion->version == $data['version'] ? 'false' : 'true');

        // how many versions have there been since the version of the user?
        $numberOfVersionsSinceUserVersion = $currentVersion->id - $userVersionId;

        // get all the release notes that have been released since the version of the user
        $releaseNotesMessage = '';
        if (! ($userVersionId == $currentVersion->id)) {
            $releaseNotes = Release::whereBetween('id', [$userVersionId, $currentVersion->id])->get();
            foreach ($releaseNotes as $releaseNote) {
                $releaseNotesMessage .= $releaseNote->notes;
            }
        }

        $json = [
            'new_version' => $isNewVersion,
            'current_version' => $currentVersion->version,
            'number_of_versions_since_user_version' => $numberOfVersionsSinceUserVersion,
            'notes' => $releaseNotesMessage
        ];

        return response()->json($json);
    }
}
