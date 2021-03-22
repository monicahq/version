<?php

namespace App\Http\Controllers;

use App\Models\Release;
use Illuminate\Http\Request;
use App\Services\CreateRelease;

class ReleaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        return view('releases.add');
    }

    public function index()
    {
        $data = [
          'releases' => Release::orderBy('id', 'desc')->get()
        ];

        return view('releases.index', $data);
    }

    public function store(Request $request)
    {
        app(CreateRelease::class)->execute([
            'version' => $request->get('version'),
            'notes' => $request->get('release'),
            'released_on' => $request->get('date'),
        ]);

        return redirect()->route('releases.index');
    }
}
