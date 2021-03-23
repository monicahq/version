<?php

namespace App\Http\Controllers;

use App\Models\Release;
use Illuminate\Http\Request;
use App\Services\CreateRelease;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Jetstream;

class ReleaseController extends Controller
{
    /**
     * Show releases.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        return Jetstream::inertia()->render($request, 'Releases', [
            'releases' => Release::orderBy('id', 'desc')->get()
        ]);
    }

    /**
     * Create a new release.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $release = app(CreateRelease::class)->execute(
            $request->only([
                'version',
                'notes',
                'released_on',
            ])
        );

        return back()->with('flash', [
            'release' => $release,
        ]);
    }

    /**
     * Update the given release.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $tokenId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $tokenId)
    {
        return back(303);
    }

    /**
     * Delete the given release.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $releaseId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $releaseId)
    {
        Release::find($releaseId)->delete();

        return back(303);
    }
}
