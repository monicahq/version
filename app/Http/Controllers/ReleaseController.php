<?php

namespace App\Http\Controllers;

use App\Models\Release;
use Validator;
use Illuminate\Http\Request;

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
        $validator = Validator::make($request->all(), [
            'version' => 'required',
            'release' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator);
        }

        $release = new Release;
        $release->version = $request->get('version');
        $release->notes = $request->get('release');
        $release->released_on = $request->get('date');
        $release->save();

        return redirect('releases');
    }
}
